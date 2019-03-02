<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\HistoryRequest;
use Illuminate\Support\Facades\DB;

use App\Eloquents\History;
use App\Eloquents\User;
use App\Eloquents\Visitor;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class EntriesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Entries Controller
    |--------------------------------------------------------------------------
    |
    | 入館 コントローラー
    | 入館一覧、削除機能を提供します。
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
     * 入館一覧画面の表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $entries = Visitor::select(DB::raw('history_id, count(id) as entry_count'))
            ->whereNull('exit_dt')
            ->groupby('history_id');

        $exits   = Visitor::select(DB::raw('history_id, count(id) as exit_count'))
            ->whereNotNull('exit_dt')
            ->groupby('history_id');

        /* 入館一覧 */
        $entries = History::select(DB::raw('histories.*, users.short_name, entry.entry_count, exits.exit_count'))
            ->leftJoin('users', function($join) {
                   $join->on('users.id', '=', 'histories.reception_user_id');
               })
            ->leftJoinSub($entries, 'entry', function($join) {
                   $join->on('entry.history_id', '=', 'histories.id');
               })
            ->leftJoinSub($exits, 'exits', function($join) {
                   $join->on('exits.history_id', '=', 'histories.id');
               })
            ->whereNull('last_dt')
            ->orderby('visit_dt', 'asc')
            ->get();

        /* レスポンス */
        return view('entries', compact('entries'));
    }

    /**
     * 退館一覧画面表示
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request)
    {
        $id = $request->input('id');

        $visitors = Visitor::select(DB::raw('visitors.*, admissions.display_no as admission_no'))
            ->leftJoin('admissions', 'admissions.id', 'visitors.admission_id')
            ->where('history_id', $id)
            ->orderby('admission_id', 'asc')
            ->get();

        /* レスポンス */
        return view('exit', compact('id', 'visitors'));
    }

    /**
     * 全退館処理
     *
     * @param HistoryRequest $request
     * @return View
     */
    public function exit(HistoryRequest $request)
    {
        $now = Carbon::now();

        $validated = $request->validated();
        $id = $validated['id'];

        DB::beginTransaction();
        try {

            // 未退館者の退館日時を設定
            Visitor::where('history_id', $id)
                ->whereNull('exit_dt')
                ->update(['exit_dt' => $now]);

            // 最終更新日時更新
            $history = History::find($id);
            $history->last_dt = $now;
            $history->save();

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

}
