<?php
/**
 * FormのInputバリデーションメッセージを表示する領域
 * 
 * @param string $element 要素名の文字列
 */
?>

@if(!empty($errors->has($element)))
    <div class="invalid-feedback">{{ $errors->first($element) }}</div>
@endif
