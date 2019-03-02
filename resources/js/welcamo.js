/**
 * This object is point of root this application's plain JS.
 *
 *
 */
var appjs = {};

/**
 * Common Field
 *
 */
appjs.FIELD = {
    EDIT_INDEX: '#edit_index',
    MODAL_CONFIRM: {
        MODAL  : '#modal-confirm',
        CANCEL : '#modal-confirm-cancel',
        OK     : '#modal-confirm-ok',
        NAME   : '#modal-confirm-form-name',
        ACTION : '#modal-confirm-form-action',
        METHOD : '#modal-confirm-form-method',
        INDEX  : '#modal-confirm-edit-index',
        TITLE  : '#modal-confirm-title',
        FIELD  : '#modal-confirm-field',
        MESSAGE: '#modal-confirm-message'
    }
}


/**
 * Common Functions
 *
 */

/**
 * appjs: submitForm()
 * 
 * マスター画面の追加、一覧アクションがあるページにおいて、指定インデックスの
 * 要素の値を、「edit_index」IDの要素値に設定して、フォームを送信する。
 * 
 * @param name      フォーム名
 * @param action    アクションURL
 * @param method    送信メソッド
 * @param index     インデックス
 * 
 */
appjs.submitForm = function(name, action, method, index) {
    var form   = $("form[name='" + name + "']");
    var edit   = $(appjs.FIELD.EDIT_INDEX);

    if (form.length) {
        $buttons = $('button');
        $buttons.each(function(i, e) {
            $(e).prop('disabled', true);
        });

        if (edit.length) {
            edit.val(index);
        }
        form.attr('action', action);
        form.attr('method', method);
        form.submit();
    }
};

/**
 * appjs: submitWithConfirm()
 * 
 * フォーム送信前に確認用のモーダルダイアログを表示する
 * 
 * @param name      フォーム名
 * @param action    アクションURL
 * @param method    送信メソッド
 * @param index     インデックス
 * @param title     モーダルタイトル
 * @param field     モーダルフィールド名
 * @param message   モーダルメッセージ
 * 
 */
appjs.submitWithConfirm = function(name, action, method, index, title, field, message) {

    // OKボタンクリック時の引継ぎ値
    $(appjs.FIELD.MODAL_CONFIRM.NAME).val(name);
    $(appjs.FIELD.MODAL_CONFIRM.ACTION).val(action);
    $(appjs.FIELD.MODAL_CONFIRM.METHOD).val(method);
    $(appjs.FIELD.MODAL_CONFIRM.INDEX).val(index);

    // モーダル画面表示内容
    $(appjs.FIELD.MODAL_CONFIRM.TITLE).text(title);
    $(appjs.FIELD.MODAL_CONFIRM.FIELD).text(field);
    $(appjs.FIELD.MODAL_CONFIRM.MESSAGE).text(message);

    // モーダル表示
    $("#modal-confirm").modal();
};

/** 
 * 確認用モーダル：キャンセルボタンクリックイベント
 *
 */
if ($(appjs.FIELD.MODAL_CONFIRM.MODAL).length > 0) {
    $(appjs.FIELD.MODAL_CONFIRM.CANCEL).on("click", function() {
        $(appjs.FIELD.MODAL_CONFIRM.MODAL).modal('hide');
    });
}

/** 
 * 退館用モーダル：決定ボタンクリックイベント
 *
 */
if ($(appjs.FIELD.MODAL_CONFIRM.MODAL).length > 0) {
    $(appjs.FIELD.MODAL_CONFIRM.OK).on("click", function() {
        var name   = $(appjs.FIELD.MODAL_CONFIRM.NAME).val();
        var action = $(appjs.FIELD.MODAL_CONFIRM.ACTION).val();
        var method = $(appjs.FIELD.MODAL_CONFIRM.METHOD).val();
        var index  = $(appjs.FIELD.MODAL_CONFIRM.INDEX).val();

        $(appjs.FIELD.MODAL_CONFIRM.MODAL).modal('hide');
        appjs.submitForm(name, action, method, index);
    });
}
