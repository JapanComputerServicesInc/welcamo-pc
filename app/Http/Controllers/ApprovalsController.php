<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryRequest;
use App\Http\Requests\SearchHistoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Eloquents\History;
use App\Eloquents\User;
use App\Eloquents\Visitor;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class ApprovalsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Approvals Controller
    |--------------------------------------------------------------------------
    |
    | 責任者確認用 コントローラー
    | 責任者確認一覧、確認機能を提供します。
    |
    | [制約]
    | 1) 認証済ユーザーのみ：configのrouteにてauthミドルウェア呼出しによる制御
    | 2) 管理ユーザーのみ　：configのrouteにてadmin認可のミドルウェア呼出しによる制御
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
     * 責任者確認一覧画面の表示
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
     * 責任者確認一覧画面の表示
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
        $year     = self::getValue($request, 'year'    , 'approvals.year'    , $today->year);
        $month    = self::getValue($request, 'month'   , 'approvals.month'   , $today->month);
        $criteria = self::getValue($request, 'criteria', 'approvals.criteria', null);
        self::setSession($request, $year, $month, $criteria);

        /* 責任者確認一覧 */
        $approvals = 
            History::fillByCriteria($year, $month, $criteria)
            ->select(DB::raw('histories.*, users.short_name'))
            ->leftJoin('users', function($join) {
                   $join->on('users.id', '=', 'histories.reception_user_id');
               })
            ->whereNotNull('last_dt')
            ->whereNull('approval_user_id')
            ->orderby('visit_dt', 'asc')
            ->get();

        /* レスポンス */
        return view('approvals',
            compact('approvals', 'years', 'today', 'year', 'month', 'criteria'));
    }

    /**
     * 責任者確認処理
     *
     * @param HistoryRequest $request
     * @return View
     */
    public function approval(HistoryRequest $request)
    {
        $validated = $request->validated();

        $history = History::find($validated['id']);
        $history->approval_user_id = Auth::id();
        $history->approval_dt      = Carbon::now();
        $history->save();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect(route('search_approvals'))->withInput();
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
            $request->session()->forget('approvals.year');
        } else {
            $request->session()->put('approvals.year', $year);
        }

        if (empty($month)) {
            $request->session()->forget('approvals.month');
        } else {
            $request->session()->put('approvals.month', $month);
        }

        if (empty($criteria)) {
            $request->session()->forget('approvals.criteria');
        } else {
            $request->session()->put('approvals.criteria', $criteria);
        }
    }

}
