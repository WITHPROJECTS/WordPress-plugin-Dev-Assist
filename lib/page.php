<?php
namespace dev_assist;

use dev_assist\Parser as Parser;

$opt = get_option( WPDA_DB_OPTIONS_NAME );

if ( isset($_POST['save_wpda']) ){
	$opt['domain']    = Paser::to_data( 'domain', $_POST['domain'] );
	$opt['parent_id'] = Paser::to_data( 'parent_id', $_POST['parent_id'] );
	update_option( WPDA_DB_OPTIONS_NAME, $opt );
}

$opt = Parser::to_html($opt);

?>
<div class="wrap">
	<h1>Dev Assist v<?php echo WPDA_VERSION; ?></h1>
	<div class="wpda-reset">
		<ul class="wpda-tab">
			<li class="active"><a href="">動作設定</a></li>
			<li><a href="">ショートコード</a></li>
			<li><a href="">PHPクラス</a></li>
		</ul>
		<form action="post" class="wpda-box setting">
			<table>
				<tbody>
					<tr>
						<th>本番環境のドメイン</th>
						<td><input type="text" name="domain"></td>
					</tr>
					<tr>
						<th>親ブログID</th>
						<td>
							<input type="number" name="parent_id" class="quarter-size">
							<p class="codicil">マルチブログの場合のみ設定</p>
						</td>
					</tr>
					<tr class="checkbox-item">
						<th>プラグイン無効化時に設定を削除する</th>
						<td><input type="checkbox" name="delete_option" <?php checked(true, $opt['oembed_block']); ?>></td>
					</tr>
					<tr>
						<th>ブレイクポイント</th>
						<td>
							<textarea name="break_point"></textarea>
							<p class="codicil">
								↓フォーマット<br>
								'small'=>'screen and (max-width: 500px)'<br>
								'large'=>'screen and (min-width: 501px)'<br>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<h2>PATH / DIRECTORY</h2>
			<table>
				<tbody>
					<tr>
						<th>開発者用外部ファイルディレクトリ</th>
						<td>
							<input type="text" name="ext_path" class="half-size" value="<?php echo $opt['ext_path']; ?>">
							<p class="codicil">テーマディレクトリからの相対パス</p>
						</td>
					</tr>
					<tr>
						<th>ユーザー用外部ファイルディレクトリ</th>
						<td>
							<input type="text" name="user_ext_path" class="half-size" value="<?php echo $opt['user_ext_path']; ?>">
							<p class="codicil">WEBルートからの相対パス</p>
						</td>
					</tr>
					<tr>
						<th>画像ディレクトリ名</th>
						<td><input type="text" name="img_dir_name" class="half-size" value="<?php echo $opt['img_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>CSSファイルディレクトリ名</th>
						<td><input type="text" name="css_dir_name" class="half-size" value="<?php echo $opt['css_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>JSファイルディレクトリ名</th>
						<td><input type="text" name="js_dir_name" class="half-size" value="<?php echo $opt['js_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>PHPファイルディレクトリ名</th>
						<td><input type="text" name="php_dir_name" class="half-size" value="<?php echo $opt['php_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>フォントファイルディレクトリ名</th>
						<td><input type="text" name="font_dir_name" class="half-size" value="<?php echo $opt['font_dir_name']; ?>"></td>
					</tr>
				</tbody>
			</table>
			<h2>META TAG</h2>
			<table>
				<tbody>
					<tr class="checkbox-item">
						<th>絵文字タグブロック</th>
						<td><input type="checkbox" name="emoji_block" <?php checked(true, $opt['emoji_block']); ?>></td>
					</tr>
					<tr class="checkbox-item">
						<th>oEmbedタグブロック</th>
						<td><input type="checkbox" name="oembed_block" <?php checked(true, $opt['oembed_block']); ?>></td>
					</tr>
					<tr class="checkbox-item">
						<th>feed link</th>
						<td><input type="checkbox" name="" <?php checked(true, $opt['oembed_block']); ?>></td>
					</tr>
				</tbody>
			</table>
			<h2>Alert</h2>
			<table>
				<tbody>
					<tr>
						<th>アラート出力</th>
						<td><select name="alert">
							<option value="disable">無効</option>
							<option value="enable">常に有効</option>
							<option value="prod_env_enable">本番環境時のみ有効</option>
						</select></td>
					</tr>
					<tr class="checkbox-item">
						<th>コメント許可状態</th>
						<td><input type="checkbox"></td>
					</tr>
					<tr class="checkbox-item">
						<th>ファイル権限</th>
						<td><input type="checkbox"></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="save_wpda" value="true">
			<?php submit_button(); ?>
		</form>
	</div>
	<p class="copy-right">(C) 2017- WITHPROJECTS inc.</p>
</div>
