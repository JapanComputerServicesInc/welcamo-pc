<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryRequest;
use App\Http\Requests\ShowHistoryRequest;
use App\Http\Requests\SearchHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

use App\Eloquents\Admission;
use App\Eloquents\History;
use App\Eloquents\Purpose;
use App\Eloquents\User;
use App\Eloquents\Visitor;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class HistoriesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Histories Controller
    |--------------------------------------------------------------------------
    |
    | 入退館履歴用 コントローラー
    | 入退館履歴一覧機能、入退館詳細画面表示機能を提供します。
    |
    | [制約]
    | 1) 認証済ユーザーのみ：configのrouteにてauthミドルウェア呼出しによる制御
    |
    */

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
    }

    /**
     * 入退館履歴一覧画面の表示
     *
     * @param SearchHistoryRequest $request
     * @return View
     */
    public function index(SearchHistoryRequest $request)
    {
        self::setSession($request, null, null, null);

        return self::search($request);
    }

    /**
     * 入退館履歴一覧検索
     *
     * @param SearchHistoryRequest $request
     * @return View
     */
    public function search(SearchHistoryRequest $request)
    {
        $nextYear  = Carbon::now()->addYear(1);
        $today     = Carbon::now();

        /* 年コンボ */
        $years = [$nextYear->format('Y')];
        for($i = 1; $i < 7; $i++) {
            array_push($years, $nextYear->subYear(1)->year);
        }

        /* 検索条件*/
        $validated = $request->validated();
        $year     = self::getValue($request, 'year'    , 'histories.year'    , $today->year);
        $month    = self::getValue($request, 'month'   , 'histories.month'   , $today->month);
        $criteria = self::getValue($request, 'criteria', 'histories.criteria', null);
        self::setSession($request, $year, $month, $criteria);

        /* 入退館履歴一覧 */
        $histories = History::fillByCriteria($year, $month, $criteria)
            ->select(DB::raw('histories.*, reception.short_name as reception_user, approval.short_name as approval_user'))
            ->leftJoinSub(DB::table('users'), 'reception', function($join) {
                $join->on('reception.id', '=', 'histories.reception_user_id');
            })
            ->leftJoinSub(DB::table('users'), 'approval', function($join) {
                $join->on('approval.id', '=', 'histories.approval_user_id');
            })
            ->orderby('visit_dt', 'asc')
            ->get();

        /* レスポンス */
        return view('histories',
            compact('histories', 'years', 'today', 'year', 'month', 'criteria'));
    }

    /**
     * 入退館詳細画面の表示
     *
     * @param ShowHistoryRequest $request
     * @return View
     */
    public function history(ShowHistoryRequest $request)
    {
        $id     = $request->input('id');
        $bname  = self::getValue($request, 'bname' , 'history.bname' , '');
        $broute = self::getValue($request, 'broute', 'history.broute', '');

        self::setHistorySession($request, $bname, $broute);

        $history      = History::find($id);
        $reception    = User::find($history->reception_user_id);
        $approval     = User::find($history->approval_user_id);
        $purpose      = Purpose::find($history->purpose_id);
        $visitors     = Visitor::select(DB::raw('visitors.*, admissions.display_no as admission_no'))
            ->leftJoin('admissions', 'admissions.id','visitors.admission_id')
            ->where('history_id', $id)
            ->get();
        $purposes     = Purpose::whereNull('deleted_at')->get();
        $admissions   = Admission::whereNotExists(function($query) use ($id) {
                $query->select(DB::raw(1))
                    ->from('visitors')
                    ->whereRaw('visitors.history_id <> ' . $id)
                    ->whereRaw('admissions.id = visitors.admission_id')
                    ->whereNull('exit_dt');
            })
            ->whereNull('deleted_at')
            ->get();
        $receptioners = User::Receptioner()->get();

        /* レスポンス */
        return view('history', compact('bname', 'broute',
            'history', 'reception', 'approval', 'purpose',
            'visitors','purposes', 'admissions', 'receptioners'));
    }

    /**
     * 入退館履歴の更新
     *
     * @param Request $request
     * @return View
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'                => 'bail|required|exists:histories,id',
            'reception_user_id' => 'bail|required|exists:users,id',
            'company_name'      => 'bail|required|max:60',
            'visitor_name'      => 'bail|required|max:20',
            'purpose_id'        => 'bail|required|exists:purposes,id',
            'purpose_remarks'   => 'bail|max:400',
        ]);

        if ($validator->fails()) {
            return redirect()->route('history', [
                    'id'     => $request->input('id'),
                    'bname'  => $request->input('bname'),
                    'broute' => $request->input('broute')
                ])
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $history = History::find($validated['id']);
        $history->reception_user_id = $validated['reception_user_id'];
        $history->company_name      = $validated['company_name'];
        $history->visitor_name      = $validated['visitor_name'];
        $history->purpose_id        = $validated['purpose_id'];
        $history->purpose_remarks   = $validated['purpose_remarks'];
        $history->save();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect()->route('history', ['id' => $request->input('id') ]);
    }

    /**
     * 入退館履歴の削除
     *
     * @param HistoryRequest $request
     * @return View
     */
    public function delete(HistoryRequest $request)
    {
        $validated = $request->validated();
        $id        = $validated['id'];

        DB::beginTransaction();
        try {
            // 訪問者の削除
            Visitor::where('history_id', $id)->delete();

            // 入退館履歴の削除
            $history = History::find($id);
            $history->delete();

            // コミット
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error($ex);
            Alert::error(__('app.message_operation_error'));
            return redirect(route('entries'));
        }

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect(route('entries'));
    }

    /**
     * リクエスト、または、セッションからの値の取得
     *
     * @param FormRequest $request
     * @param string $key
     * @param string $sessionKey
     * @param string $default
     * @return string
     */
    private function getValue($request, $key, $sessionKey, $default)
    {
        $validated = $request->validated();

        if (array_key_exists($key, $validated)) {
            return $validated[$key];
        }

        if ($request->session()->has($sessionKey)) {
            return session($sessionKey);
        }

        return $default;
    }

    /**
     * セッションの設定
     *
     * @param SearchHistoryRequest $request
     * @param string $year
     * @param string $month
     * @param string $criteria
     */
    private function setSession(SearchHistoryRequest $request, $year, $month, $criteria)
    {
        if (empty($year)) {
            $request->session()->forget('histories.year');
        } else {
            $request->session()->put('histories.year', $year);
        }

        if (empty($month)) {
            $request->session()->forget('histories.month');
        } else {
            $request->session()->put('histories.month', $month);
        }

        if (empty($criteria)) {
            $request->session()->forget('histories.criteria');
        } else {
            $request->session()->put('histories.criteria', $criteria);
        }
    }

    /**
     * 詳細画面用セッションの設定
     *
     * @param FormRequest $request
     * @param string $bname
     * @param string $broute
     */
    private function setHistorySession($request, $bname, $broute)
    {
        if (empty($bname)) {
            $request->session()->forget('history.bname');
        } else {
            $request->session()->put('history.bname', $bname);
        }

        if (empty($broute)) {
            $request->session()->forget('history.broute');
        } else {
            $request->session()->put('history.broute', $broute);
        }
    }
}
