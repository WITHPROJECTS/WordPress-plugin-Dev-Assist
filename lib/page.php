<?php
namespace dev_assist;

use dev_assist\Parser as Parser;

$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
$opt        = $db_manager->get();

if ( isset($_POST['wpda_save']) ){
	$opt['site_path']             = $_POST['wpda_site_path'];                                    // WEBルートからサイトまでの相対パス
	$opt['domain']                = $_POST['wpda_domain'];                                       // 本番環境ドメイン
	$opt['parent_id']             = $_POST['wpda_parent_id'];                                    // 親ブログID
	$opt['delete_option']         = isset( $_POST['wpda_delete_option'] ) ? true : false;        // プラグイン無効化時にDBから設定を削除する
	$opt['break_point']           = $_POST['wpda_break_point'];                                  // ブレイクポイント
	$opt['author_page_redirect']  = isset( $_POST['wpda_author_page_redirect'] ) ? true : false; // 著者ページリダイレクト
	$opt['title_tag']             = isset( $_POST['wpda_title_tag'] ) ? true : false;            // 著者ページリダイレクト
	$opt['show_admin_bar']        = isset( $_POST['wpda_show_admin_bar'] ) ? true : false;       // 管理バーの表示
	$opt['ext_path']              = $_POST['wpda_ext_path'];                                     // 開発者用外部ファイルディレクトリ
	$opt['user_ext_path']         = $_POST['wpda_user_ext_path'];                                // ユーザー用外部ファイルディレクトリ
	$opt['img_dir_name']          = $_POST['wpda_img_dir_name'];                                 // 画像ディレクトリ名
	$opt['css_dir_name']          = $_POST['wpda_css_dir_name'];                                 // CSSディレクトリ名
	$opt['js_dir_name']           = $_POST['wpda_js_dir_name'];                                  // JSファイルディレクトリ
	$opt['php_dir_name']          = $_POST['wpda_php_dir_name'];                                 // PHPファイルディレクトリ
	$opt['font_dir_name']         = $_POST['wpda_font_dir_name'];                                // フォントファイルディレクトリ
	$opt['emoji_block']           = isset( $_POST['wpda_emoji_block'] ) ? true : false;          // 絵文字タグブロック
	$opt['oembed_block']          = isset( $_POST['wpda_oembed_block'] ) ? true : false;         // oEmbedタグブロック
	$opt['feed_block']            = isset( $_POST['wpda_feed_block'] ) ? true : false;           // フィードタグブロック
	$opt['canonical_block']       = isset( $_POST['wpda_canonical_block'] ) ? true : false;      // カノニカルタグブロック
	$opt['alert']                 = $_POST['wpda_alert'];                                        // アラート出力
	$opt['comment_alert']         = isset( $_POST['wpda_comment_alert'] ) ? true : false;        // コメント許可状態の場合アラート
	$opt['file_permision_alert']  = isset( $_POST['wpda_file_permision_alert'] ) ? true : false; // 適切なファイル権限では無い場合アラート

	$opt = Parser::form2data($opt);
	$db_manager->update( $opt );
	// update_option( WPDA_DB_OPTIONS_NAME, $opt );
}

$opt = Parser::data2html($opt);

?>
<div class="wrap">
	<?php
		// var_dump($PATH);
	?>
	<h1>Dev Assist v<?php echo WPDA_VERSION; ?></h1>
	<div class="wpda-reset">
		<ul class="wpda-tab">
			<li class="active"><a href="">動作設定</a></li>
			<li><a href="">ショートコード</a></li>
			<li><a href="">PHPクラス</a></li>
		</ul>
		<!-- ///////////////////////////////////////////////////////////////////
		動作設定
		//////////////////////////////////////////////////////////////////// -->
		<form action="" method="POST" class="wpda-box setting">
			<table>
				<tbody>
					<tr>
						<th>WEBルートからサイトまでの相対パス</th>
						<td><input type="text" name="wpda_site_path" value="<?php echo $opt['site_path']; ?>"></td>
					</tr>
					<tr>
						<th>本番環境のドメイン</th>
						<td><input type="text" name="wpda_domain" value="<?php echo $opt['domain']; ?>"></td>
					</tr>
					<tr>
						<th>親ブログID</th>
						<td>
							<input type="number" name="wpda_parent_id" class="quarter-size" value="<?php echo $opt['parent_id']; ?>">
							<p class="codicil">マルチブログの場合のみ設定</p>
						</td>
					</tr>
					<tr class="checkbox-item">
						<th>プラグイン無効化時に設定を削除する</th>
						<td><input type="checkbox" name="wpda_delete_option" <?php checked(true, $opt['delete_option']); ?>></td>
					</tr>
					<tr>
						<th>ブレイクポイント</th>
						<td>
							<textarea name="wpda_break_point"><?php echo $opt['break_point']; ?></textarea>
							<p class="codicil">
								↓フォーマット<br>
								small=>screen and (max-width: 500px)<br>
								large=>screen and (min-width: 501px)<br>
							</p>
						</td>
					</tr>
					<tr>
						<th>著者ページリダイレクト</th>
						<td><input type="checkbox" name="wpda_author_page_redirect" <?php checked(true, $opt['author_page_redirect']); ?>></td>
					</tr>
					<tr>
						<th>タイトルタグ出力</th>
						<td><input type="checkbox" name="wpda_title_tag" <?php checked(true, $opt['title_tag']); ?>></td>
					</tr>
					<tr>
						<th>管理バー表示</th>
						<td><input type="checkbox" name="wpda_show_admin_bar" <?php checked(true, $opt['show_admin_bar']); ?>></td>
					</tr>
				</tbody>
			</table>
			<h2>PATH / DIRECTORY</h2>
			<table>
				<tbody>
					<tr>
						<th>開発者用外部ファイルディレクトリパス</th>
						<td>
							<input type="text" name="wpda_ext_path" class="half-size" value="<?php echo $opt['ext_path']; ?>">
							<p class="codicil">
								テーマディレクトリからの相対パス<br>
								現在のパス：
							</p>
						</td>
					</tr>
					<tr>
						<th>ユーザー用外部ファイルディレクトリパス</th>
						<td>
							<input type="text" name="wpda_user_ext_path" class="half-size" value="<?php echo $opt['user_ext_path']; ?>">
							<p class="codicil">
								サイトからの相対パス<br>
								現在のパス：
							</p>
						</td>
					</tr>
					<tr>
						<th>画像ディレクトリ名</th>
						<td><input type="text" name="wpda_img_dir_name" class="half-size" value="<?php echo $opt['img_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>CSSファイルディレクトリ名</th>
						<td><input type="text" name="wpda_css_dir_name" class="half-size" value="<?php echo $opt['css_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>JSファイルディレクトリ名</th>
						<td><input type="text" name="wpda_js_dir_name" class="half-size" value="<?php echo $opt['js_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>PHPファイルディレクトリ名</th>
						<td><input type="text" name="wpda_php_dir_name" class="half-size" value="<?php echo $opt['php_dir_name']; ?>"></td>
					</tr>
					<tr>
						<th>フォントファイルディレクトリ名</th>
						<td><input type="text" name="wpda_font_dir_name" class="half-size" value="<?php echo $opt['font_dir_name']; ?>"></td>
					</tr>
				</tbody>
			</table>
			<h2>META TAG</h2>
			<table>
				<tbody>
					<tr class="checkbox-item">
						<th>絵文字タグブロック</th>
						<td><input type="checkbox" name="wpda_emoji_block" <?php checked(true, $opt['emoji_block']); ?>></td>
					</tr>
					<tr class="checkbox-item">
						<th>oEmbedタグブロック</th>
						<td><input type="checkbox" name="wpda_oembed_block" <?php checked(true, $opt['oembed_block']); ?>></td>
					</tr>
					<tr class="checkbox-item">
						<th>Feedタグブロック</th>
						<td><input type="checkbox" name="wpda_feed_block" <?php checked(true, $opt['feed_block']); ?>></td>
					</tr>
					<tr class="checkbox-item">
						<th>カノニカルタグブロック</th>
						<td><input type="checkbox" name="wpda_canonical_block" <?php checked(true, $opt['canonical_block']); ?>></td>
					</tr>
				</tbody>
			</table>
			<h2>ALERT</h2>
			<table>
				<tbody>
					<tr>
						<th>アラート出力</th>
						<td><select name="wpda_alert">
							<option value="disable" <?php selected($opt['alert'], 'disable'); ?>>無効</option>
							<option value="enable" <?php selected($opt['alert'], 'enable'); ?>>常に有効</option>
							<option value="prod_env_enable" <?php selected($opt['alert'], 'prod_env_enable'); ?>>本番環境時のみ有効</option>
						</select></td>
					</tr>
					<tr class="checkbox-item">
						<th>コメント許可状態</th>
						<td><input type="checkbox" name="wpda_comment_alert" <?php checked(true, $opt['comment_alert']); ?>></td>
					</tr>
					<tr class="checkbox-item">
						<th>ファイル権限</th>
						<td><input type="checkbox" name="wpda_file_permision_alert" <?php checked(true, $opt['file_permision_alert']); ?>></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="wpda_save" value="true">
			<?php submit_button(); ?>
		</form>
		<!-- ///////////////////////////////////////////////////////////////////
		ショートコード
		//////////////////////////////////////////////////////////////////// -->
		<div class="wpda-box shortcode">
		</div>
		<!-- ///////////////////////////////////////////////////////////////////
		PHPクラス
		//////////////////////////////////////////////////////////////////// -->
		<div class="wpda-box php-class">
			<ul>
				<li>
					<a href="">UA</a>
					<ul>
						<li><a href="">UA::get_ua()</a></li>
						<li><a href="">UA::get_device_name()</a></li>
						<li><a href="">UA::is_smartphone()</a></li>
						<li><a href="">UA::is_tablet()</a></li>
						<li><a href="">UA::is_pc()</a></li>
					</ul>
				</li>
				<li>
					<a href="">WP_Path</a>
					<ul>
						<li><a href="">UA::get_ua()</a></li>
						<li><a href="">UA::get_device_name()</a></li>
						<li><a href="">UA::is_smartphone()</a></li>
						<li><a href="">UA::is_tablet()</a></li>
						<li><a href="">UA::is_pc()</a></li>
					</ul>
				</li>
				<li>
					<a href="">WP_Helper</a>
					<ul>
						<li><a href="">WP_Helper::slug2id()</a></li>
						<li><a href="">WP_Helper::get_root_page()</a></li>
						<li><a href="">WP_Helper::is_smartphone()</a></li>
						<li><a href="">WP_Helper::is_tablet()</a></li>
						<li><a href="">WP_Helper::is_pc()</a></li>
					</ul>
				</li>
			</ul>
			<div>
				<div>
					<h2>dev_assist\UA</h2>
				</div>
				<div>
					<h2>dev_assist\WP_Path</h2>
				</div>
			</div>
		</div>
	</div>
	<p class="copy-right">(C) 2017- WITHPROJECTS inc.</p>
</div>
