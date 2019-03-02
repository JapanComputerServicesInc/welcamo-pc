<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Eloquents\History;
use App\Eloquents\Visitor;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class VisitorsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Visitors Controller
    |--------------------------------------------------------------------------
    |
    | 入退館者 コントローラー
    | 入館者サイン画像の表示、入館証Noの更新、入退館処理機能を提供します。
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
     * 入館証No更新処理
     *
     * @param UpdateVisitorRequest $request
     * @return View
     */
    public function update(UpdateVisitorRequest $request)
    {
        $validated = $request->validated();

        $visitor = Visitor::find($validated['visitor_id']);
        $visitor->admission_id = $validated['admission_id'];
        $visitor->save();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect()->route('history', ['id' => $validated['id']]);
    }

    /**
     * 退館処理
     *
     * @param VisitorRequest $request
     * @return View
     */
    public function exit(VisitorRequest $request)
    {
        $now = Carbon::now();

        $validated  = $request->validated();
        $history_id = $validated['id'];

        DB::beginTransaction();
        try {

            // 入館者の退館日時を設定
            $visitor = Visitor::find($validated['visitor_id']);
            $visitor->exit_dt = $now;
            $visitor->save();

            // 未退館者の数を取得
            $count = Visitor::where('history_id', $history_id)
                ->whereNull('exit_dt')
                ->count();

            // 最終更新日時更新
            if ($count == 0) {
                $history = History::find($history_id);
                $history->last_dt = $now;
                $history->save();
            }

            // コミット
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error($ex);
            Alert::error(__('app.message_operation_error'));
            return redirect()->route('history', ['id' => $validated['id']]);
        }

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect()->route('history', ['id' => $validated['id']]);
    }

    /**
     * 退館解除処理
     *
     * @param VisitorRequest $request
     * @return View
     */
    public function cancel(VisitorRequest $request)
    {
        $now = Carbon::now();

        $validated  = $request->validated();
        $history_id = $validated['id'];

        DB::beginTransaction();
        try {

            // 入館者の退館日時をNULLに設定
            $visitor = Visitor::find($validated['visitor_id']);
            $visitor->exit_dt = null;
            $visitor->save();

            // 入退館履歴の最終退館日時を更新
            $history = History::find($history_id);
            if (!empty($history->last_dt)) {
                $history->last_dt = null;
                $history->save();
            }

            // コミット
            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error($ex);
            Alert::error(__('app.message_operation_error'));
            return redirect()->route('history', ['id' => $validated['id']]);
        }

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect()->route('history', ['id' => $validated['id']]);
    }

    /**
     * サインの画像の表示
     *
     * @param int @id
     * @return View
     */
    public function signature($id)
    {
        $visitor = Visitor::find($id);
        if (!$visitor) {
            abort('404');
        }

        /* バイナリイメージのレスポンス */
        return response()->make(
            $visitor->signature,
            200,
            ['Content-type' => 'image/png']
        );
    }

}
