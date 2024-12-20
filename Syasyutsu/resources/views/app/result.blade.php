@extends('layouts.base_result')

@section('title','ログ　検索結果ページ')

@section('headder')
<div class="head w3-display-container w3-teal">
	<div class="w3-display-left">
		<h1>ログ検索ページ</h1>
	</div>
	<div class="w3-display-right">
		<a href="/" style="text-decoration:none;">
			<p class="w3-sans-serif">LOGO&emsp;</p>
		</a>
	</div>
</div>
@endsection

@section('main')

@switch($method)
	@case("all")
		<p>検索：全期間検索</p>
	@break
	@case("paraName")
		<p>検索：パラメータ名検索</p>
		<p>内容：{{$q}}</p>
	@break
	@case("date")
		<p>検索：日付検索</p>
		<p>内容：{{$q}}</p>
	@break
	@case("range")
		<p>検索：期間検索</p>
		<p>内容：{{$q1}} ~ {{$q2}}</p>
	@break
	@case("judgment")
		<p>検索：良品・不良品検索</p>
		<p>内容：{{$q}}</p>
	@break
@endswitch

<p>{{ $logs->total() }}件中
@php
	echo count($logs) ."件表示</p>"
@endphp
	

<div class="content1 w3-display-container">
	<div class="content2 w3-display-topright">
		<form action="{{ route('export/csv') }}" method="get">
		@csrf
			<input type="hidden" name="method" value="{{ $method }}" >
		@if($method == "range")
			<input type="hidden" name="q1" value="{{ $q1 }}" >
			<input type="hidden" name="q2" value="{{ $q2 }}" >
		@else
			<input type="hidden" name="q" value="{{ $q }}" >
		@endif
			<label>検索結果をダウンロード
			<input class="download-btn" type="submit" value="CSVダウンロード">
			</label>
		</form>
	</div>
	<div class="content3 w3-display-middle w3-display-container">
		<table border="1" class="w3-display-middle">
			<thead>
			<tr>
				<th>番号</th>
				<th>画像名(上)</th>
				<th>画像名(横)</th>
				<th>パラメータ名</th>
				<th>横幅</th>
				<th>縦幅</th>
				<th>高さ</th>
				<th>判定</th>
				<th>作成日</th>
				<th>上部の画像</th>
				<th>横側の画像</th>
			</tr>
			</thead>

			<tbody>
		@if(!isset($logs[0]))
			<tr>
				<td colspan="5">NO DATA EXISTS!!</td>
			</tr>

		@else
		@foreach($logs as $log)
			<tr>
				<td>{{$log["id"]}}</td>
				<td>{{$log["name_upper"]}}</td>
				<td>{{$log["name_side"]}}</td>
				<td>{{$log["paraName"]}}</td>
				<td>{{$log["width"]}}</td>
				<td>{{$log["length"]}}</td>
				<td>{{$log["height"]}}</td>
				<td>
					@if($log["judgment"] == 1)
						良品
					@else
						不良品
					@endif
				</td>
				<td>{{$log["year"]}}_{{$log["month"]}}_{{$log["day"]}}_{{$log["time"]}}</td>
				<td>
					<div id='{{ $log["id"] }}_img_upper'>
						<a href="http://192.168.11.13/nas/pictures/{{ $log['year'] }}_{{ $log['month'] }}_{{ $log['day'] }}/{{ $log['name_upper'] }}.jpg" data-lightbox="abc" data-title="{{ $log['name'] }}">
							<img src="http://192.168.11.13/nas/pictures/{{ $log['year'] }}_{{ $log['month'] }}_{{ $log['day'] }}/{{ $log['name_upper'] }}.jpg" width="60px" alt="none">
						</a>
					</div>
				</td>
				<td>
					<div id='{{ $log["id"] }}_img_side'>
						<a href="http://192.168.11.13/nas/pictures/{{ $log['year'] }}_{{ $log['month'] }}_{{ $log['day'] }}/{{ $log['name_side'] }}.jpg" data-lightbox="abc" data-title="{{ $log['name'] }}">
							<img src="http://192.168.11.13/nas/pictures/{{ $log['year'] }}_{{ $log['month'] }}_{{ $log['day'] }}/{{ $log['name_side'] }}.jpg" width="60px" alt="none">
						</a>
					</div>
				</td>
				<div>
					<script>
						//NAS内部の画像を探し、無い場合はダウンロード不可とする。
						function chk(url) {
							return new Promise(function (resolve, reject) {
								const img = new Image();
								let timer;
								
								// タイムアウトを設定（例えば5秒）
								const timeout = new Promise((_, reject) => {
									timer = setTimeout(() => {
										reject(new Error("Timeout"));
									}, 100); // 100ミリ秒（0.1秒）
								});

								img.src = url;
								img.onload = function () {
									clearTimeout(timer); // タイムアウトのクリア
									resolve(url);
								};
								img.onerror = function () {
									clearTimeout(timer); // タイムアウトのクリア
									reject(url);
								};

								// imgのロードとタイムアウトの競合
								Promise.race([timeout, new Promise((resolve, reject) => {
									img.onload = function () { resolve(url); };
									img.onerror = function () { reject(url); };
								})]).then(resolve).catch(reject);
							});
						}

						chk("http://192.168.11.13/nas/pictures/{{ $log['year'] }}_{{ $log['month'] }}_{{ $log['day'] }}/{{ $log['name_upper'] }}.jpg")
							.catch((url) => {
								document.getElementById('{{ $log["id"] }}_img_upper').innerHTML = "none";
								document.getElementById('{{ $log["id"] }}').innerHTML = "ダウンロード不可";
							});

						chk("http://192.168.11.13/nas/pictures/{{ $log['year'] }}_{{ $log['month'] }}_{{ $log['day'] }}/{{ $log['name_upper'] }}.jpg")
							.catch((url) => {
								document.getElementById('{{ $log["id"] }}_img_upper').innerHTML = "none";
								document.getElementById('{{ $log["id"] }}').innerHTML = "ダウンロード不可";
							});

					</script>
				</div>
				<td>
					<form method="get" action="{{ route('export/image') }}">
						<input type="hidden" name="log" value="{{ $log }}" />
						<div id='{{ $log["id"] }}'>
							<input type="submit"  value="画像ダウンロード" />
						</div>
						
					</form>
				</td>
			</tr>
		@endforeach
		@endif
			</tbody>
		</table>
		
	</div>
</div>
{{ $logs->appends(request()->query())->links()}}
@endsection


@section('bottom-links')
<hr>
<a href='/search'>検索ページへ戻る</a>
<br>
<a href='/index'>トップページへ戻る</a>
@endsection
