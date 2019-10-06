@extends('layouts.admin')
@section('title', 'Канфігурацыя кампаніі')
@section('top_scripts')
<script src="js/jscolor.js"></script>
@endsection
@section('content')
    <div class="breadcrumbs">Адмін > Галоўная старонка > Канфігурацыя модуля</div>
	<form action="/doika/save-configurations" method="post" class="form">

		<h2>Асабістыя дадзеныя</h2>
		<div class="row">
			<div class="input">
				<label for="conf-login" class="conf-title">E-mail</label>
				<input type="text" placeholder="Login" id="conf-login" name="login" class="input__input" value="{{ Auth::user()->email }}">
			</div>

		</div>
		<div class="row">
			<div class="input">
				<label for="conf-password" class="conf-title">Новы пароль</label>
				<input type="password" id="conf-password" name="password" class="input__input" value="{{ isset($password) ? $password : '' }}">
			</div>
			<div class="input">
				<label for="conf-password-confirm" class="conf-title">Пацвержанне новага паролю</label>
				<input type="password" id="conf-password-confirm" name="conf-password-confirm" class="input__input" value="{{ isset($password_confirm) ? $password_confirm : '' }}">
			</div>
		</div>

		<h2>Наладкі выгляду</h2>

		<div class="row">
			<div class="input">
				<label for="conf-color" class="conf-title checkbox-title">Паказваць банэр?</label>
				<input class="checkbox-test-payments" type="checkbox" name="show_banner" {{isset($show_banner) ? 'checked' : ''}}>
			</div>

			<div class="input">
				<button class="jscolor {valueElement:'color_banner_background', hash:true, uppercase:false}">Колер фону банэра</button>
				<input id="color_banner_background" name="color_banner_background" value="{{ isset($color_banner_background) ? $color_banner_background : '' }}" style="display: none">
			</div>
		</div>
		<div class="row">
			<div class="input">
				<button class="jscolor {valueElement:'color_banner_help_background', hash:true, uppercase:false}">Колер кнопкі "Дапамагчы"</button>
				<input id="color_banner_help_background" name="color_banner_help_background" value="{{ isset($color_banner_help_background) ? $color_banner_help_background : '' }}" style="display: none">
			</div>
			<div class="input">
				<button class="jscolor {valueElement:'color_banner_help_text', hash:true, uppercase:false}">Колер тэкста "Дапамагчы"</button>
				<input id="color_banner_help_text" name="color_banner_help_text" value="{{ isset($color_banner_help_text) ? $color_banner_help_text : '' }}" style="display: none">
			</div>
		</div>
		<div class="row">
			<div class="input">
				<button class="jscolor {valueElement:'color_module_background', hash:true, uppercase:false}">Колер фона модуля</button>
				<input id="color_module_background" name="color_module_background" value="{{ isset($color_module_background) ? $color_module_background : '' }}" style="display: none">
			</div>
			<div class="input">
				<button class="jscolor {valueElement:'color_module_buttons', hash:true, uppercase:false}">Колер кнопак з сумамі</button>
				<input id="color_module_buttons" name="color_module_buttons" value="{{ isset($color_module_buttons) ? $color_module_buttons : '' }}" style="display: none">
			</div>
		</div>

		<button class="submit-button main-buttons__create-campaign">Захаваць</button>
	</form>
@endsection
