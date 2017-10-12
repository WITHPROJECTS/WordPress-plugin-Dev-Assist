<?php
namespace dev_assist;

use dev_assist\Parser as Parser;

$db_manager = new WP_DB_manager( WPDA_MULTISITE, WPDA_DB_OPTIONS_NAME );
$opt        = $db_manager->get();

if ( isset($_POST['wpda_save']) ){
	$opt['site_path']            = $_POST['wpda_site_path'];                                    // WEBルートからサイトまでの相対パス
	$opt['domain']               = $_POST['wpda_domain'];                                       // 本番環境ドメイン
	$opt['parent_id']            = $_POST['wpda_parent_id'];                                    // 親ブログID
	$opt['delete_option']        = isset( $_POST['wpda_delete_option'] ) ? true : false;        // プラグイン無効化時にDBから設定を削除する
	$opt['media_query']          = $_POST['wpda_media_query'];                                  // ブレイクポイント
	$opt['author_page_redirect'] = isset( $_POST['wpda_author_page_redirect'] ) ? true : false; // 著者ページリダイレクト
	$opt['title_tag']            = isset( $_POST['wpda_title_tag'] ) ? true : false;            // 著者ページリダイレクト
	$opt['show_admin_bar']       = isset( $_POST['wpda_show_admin_bar'] ) ? true : false;       // 管理バーの表示
	$opt['ext_path']             = $_POST['wpda_ext_path'];                                     // 開発者用外部ファイルディレクトリ
	$opt['user_ext_path']        = $_POST['wpda_user_ext_path'];                                // ユーザー用外部ファイルディレクトリ
	$opt['img_dir_name']         = $_POST['wpda_img_dir_name'];                                 // 画像ディレクトリ名
	$opt['css_dir_name']         = $_POST['wpda_css_dir_name'];                                 // CSSディレクトリ名
	$opt['js_dir_name']          = $_POST['wpda_js_dir_name'];                                  // JSファイルディレクトリ
	$opt['php_dir_name']         = $_POST['wpda_php_dir_name'];                                 // PHPファイルディレクトリ
	$opt['font_dir_name']        = $_POST['wpda_font_dir_name'];                                // フォントファイルディレクトリ
	$opt['emoji_block']          = isset( $_POST['wpda_emoji_block'] ) ? true : false;          // 絵文字タグブロック
	$opt['oembed_block']         = isset( $_POST['wpda_oembed_block'] ) ? true : false;         // oEmbedタグブロック
	$opt['feed_block']           = isset( $_POST['wpda_feed_block'] ) ? true : false;           // フィードタグブロック
	$opt['canonical_block']      = isset( $_POST['wpda_canonical_block'] ) ? true : false;      // カノニカルタグブロック
	$opt['alert']                = $_POST['wpda_alert'];                                        // アラート出力
	$opt['comment_alert']        = isset( $_POST['wpda_comment_alert'] ) ? true : false;        // コメント許可状態の場合アラート
	$opt['pinback_alert']        = isset( $_POST['wpda_pinback_alert'] ) ? true : false;        // ピンバック・トラックバック許可
	$opt['file_permision_alert'] = isset( $_POST['wpda_file_permision_alert'] ) ? true : false; // 適切なファイル権限では無い場合アラート

	$opt = Parser::form2data($opt);
	$db_manager->update( $opt );
	// update_option( WPDA_DB_OPTIONS_NAME, $opt );
}

$opt = Parser::data2html($opt);

?>
<div class="wrap">
	<h1>Dev Assist v<?php echo WPDA_VERSION; ?></h1>
	<div class="wpda-reset">
		<ul class="wpda-tab">
			<li><a href="#setting">動作設定</a></li>
			<li><a href="#shortcode">ショートコード</a></li>
			<li><a href="#functions">関数</a></li>
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
						<th>メディアクエリ</th>
						<td>
							<textarea name="wpda_media_query"><?php echo $opt['media_query']; ?></textarea>
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
						<th>ピンバック・トラックバック受付</th>
						<td><input type="checkbox" name="wpda_pinback_alert" <?php checked(true, $opt['pinback_alert']); ?>></td>
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
			<section>
				<h2>[url blog="" path=""]</h2>
				<p class="desc-text">
					URLを出力する
				</p>
				<table>
					<thead><tr>
						<th>オプション</th>
						<th>必須</th>
						<th>初期値</th>
						<th>備考</th>
					</tr></thead>
					<tbody>
					<tr>
						<td>blog</td>
						<td></td>
						<td>'active'</td>
						<td></td>
					</tr>
					<tr>
						<td>path</td>
						<td></td>
						<td>''</td>
						<td>出力されたパスに追加するパス</td>
					</tr>
					</tbody>
				</table>
			</section>
			<section>
				<h2>[src url="" blog="" from="" dirname="" file=""]</h2>
				<p class="desc-text">
					ソースパスかurlを出力する
				</p>
				<table>
					<thead><tr>
						<th>オプション</th>
						<th>必須</th>
						<th>初期値</th>
						<th>備考</th>
					</tr></thead>
					<tbody>
					<tr>
						<td>url</td>
						<td></td>
						<td>true</td>
						<td></td>
					</tr>
					<tr>
						<td>blog</td>
						<td></td>
						<td>'active'</td>
						<td></td>
					</tr>
					<tr>
						<td>from</td>
						<td></td>
						<td>'theme'</td>
						<td></td>
					</tr>
					<tr>
						<td>dir_name</td>
						<td></td>
						<td>''</td>
						<td>動作設定で設定したディレクトリ名</td>
					</tr>
					<tr>
						<td>file</td>
						<td>◯</td>
						<td>''</td>
						<td></td>
					</tr>
					</tbody>
				</table>
			</section>
			<section>
				<h2>[img blog="" from="" (...HTML attr)]</h2>
				<p class="desc-text">
					imgタグを出力する<br>
					imgタグのattributeが利用できる
				</p>
				<table>
					<thead><tr>
						<th>オプション</th>
						<th>必須</th>
						<th>初期値</th>
						<th>備考</th>
					</tr></thead>
					<tbody>
					<tr>
						<td>blog</td>
						<td></td>
						<td>'active'</td>
						<td></td>
					</tr>
					<tr>
						<td>from</td>
						<td></td>
						<td>'theme'</td>
						<td>検索対象 themeかuser</td>
					</tr>
					</tbody>
				</table>
			</section>
			<section>
				<h2>[img-url blog="" file="" from=""]</h2>
				<p class="desc-text">画像のurlを出力する</p>
				<table>
					<thead><tr>
						<th>オプション</th>
						<th>必須</th>
						<th>初期値</th>
						<th>備考</th>
					</tr></thead>
					<tbody>
					<tr>
						<td>blog</td>
						<td></td>
						<td>'active'</td>
						<td></td>
					</tr>
					<tr>
						<td>file</td>
						<td>◯</td>
						<td></td>
						<td>ファイルまでのパス</td>
					</tr>
					<tr>
						<td>from</td>
						<td></td>
						<td>'theme'</td>
						<td>検索対象 themeかuser</td>
					</tr>
					</tbody>
				</table>
			</section>
			<section>
				<h2>[page-img from="" (...HTML attr)]</h2>
				<p class="desc-text">
					ページ用画像を利用したimgタグを出力する<br>
					imgタグのattributeが利用できる
				</p>
				<table>
					<thead><tr>
						<th>オプション</th>
						<th>必須</th>
						<th>初期値</th>
						<th>備考</th>
					</tr></thead>
					<tbody>
					<tr>
						<td>from</td>
						<td></td>
						<td>'theme'</td>
						<td>検索対象。themeかuser</td>
					</tr>
					</tbody>
				</table>
			</section>
			<section>
				<h2>[page-img-url file="" from=""]</h2>
				<p class="desc-text">ページ用画像のurlを出力する</p>
				<table>
					<thead><tr>
						<th>オプション</th>
						<th>必須</th>
						<th>初期値</th>
						<th>備考</th>
					</tr></thead>
					<tbody>
					<tr>
						<td>file</td>
						<td>◯</td>
						<td></td>
						<td>ファイルまでのパス</td>
					</tr>
					<tr>
						<td>from</td>
						<td></td>
						<td>'theme'</td>
						<td>検索対象。themeかuser</td>
					</tr>
					</tbody>
				</table>
			</section>
			<section>
				<h2>[php-include type="" blog="" file="" from=""]</h2>
				<p class="desc-text">PHPファイルを読み込み</p>
				<table>
					<thead><tr>
						<th>オプション</th>
						<th>必須</th>
						<th>初期値</th>
						<th>備考</th>
					</tr></thead>
					<tbody>
					<tr>
						<td>type</td>
						<td></td>
						<td>'require_once'</td>
						<td>"require", "require_once", "include", "include_once"</td>
					</tr>
					<tr>
						<td>blog</td>
						<td></td>
						<td>'active'</td>
						<td></td>
					</tr>
					<tr>
						<td>file</td>
						<td>◯</td>
						<td></td>
						<td>ファイルまでのパス</td>
					</tr>
					<tr>
						<td>from</td>
						<td></td>
						<td>'theme'</td>
						<td>検索対象。themeかuser</td>
					</tr>
					</tbody>
				</table>
			</section>
		</div>
		<!-- ///////////////////////////////////////////////////////////////////
		関数
		//////////////////////////////////////////////////////////////////// -->
		<div class="wpda-box functions">
			<ul>
				<li>
					<a href="">Path</a>
					<ul>
						<li><a href="#join">wpda_join()</a></li>
						<li><a href="#get_src">wpda_get_src()</a></li>
						<li><a href="#src">wpda_src()</a></li>
						<li><a href="#get_theme_url">wpda_get_theme_url()</a></li>
						<li><a href="#theme_url">wpda_theme_url()</a></li>
						<li><a href="#get_theme_path">wpda_get_theme_path()</a></li>
						<li><a href="#theme_path">wpda_theme_path()</a></li>
						<li><a href="#get_user_url">wpda_get_user_url()</a></li>
						<li><a href="#user_url">wpda_user_url()</a></li>
						<li><a href="#get_user_path">wpda_get_user_path()</a></li>
						<li><a href="#user_path">wpda_user_path()</a></li>
						<li><a href="#get_page">wpda_get_page()</a></li>
						<li><a href="#page">wpda_page()</a></li>
						<li><a href="#get_page_img_url">wpda_get_page_img_url()</a></li>
						<li><a href="#page_img_url">wpda_page_img_url()</a></li>
						<li><a href="#get_page_css_url">wpda_get_page_css_url()</a></li>
						<li><a href="#page_css_url">wpda_page_css_url()</a></li>
						<li><a href="#get_page_js_url">wpda_get_page_js_url()</a></li>
						<li><a href="#page_js_url">wpda_page_js_url()</a></li>
						<li><a href="#get_page_php_path">wpda_get_page_php_path()</a></li>
						<li><a href="#page_php_path">wpda_page_php_path()</a></li>
						<li><a href="#get_blog_url">wpda_get_blog_url()</a></li>
						<li><a href="#blog_url">wpda_blog_url()</a></li>
						<li><a href="#get_page_path">wpda_get_page_path()</a></li>
						<li><a href="#page_path">wpda_page_path()</a></li>
					</ul>
				</li>
				<li>
					<a href="">UA</a>
					<ul>
						<li><a href="#get_ua">wpda_get_ua()</a></li>
						<li><a href="#get_device_name">wpda_get_device_name()</a></li>
						<li><a href="#is_smartphone">wpda_is_smartphone()</a></li>
						<li><a href="#is_tablet">wpda_is_tablet()</a></li>
						<li><a href="#is_pc">wpda_is_pc()</a></li>
					</ul>
				</li>
				<li>
					<a href="">Helper</a>
					<ul>
						<li><a href="#path2id">wpda_path2id()</a></li>
						<li><a href="#get_post_history">wpda_get_post_history()</a></li>
						<li><a href="#get_root_post">wpda_get_root_post()</a></li>
						<li><a href="#get_term_history">wpda_get_term_history()</a></li>
						<li><a href="#get_root_term">wpda_get_root_term()</a></li>
						<li><a href="#is_tax_ancestor_of">wpda_is_tax_ancestor_of()</a></li>
						<li><a href="#is_post_ancestor_of">wpda_is_post_ancestor_of()</a></li>
						<li><a href="#is_child_post">wpda_is_child_post()</a></li>
						<li><a href="#has_child_page">wpda_has_child_page()</a></li>
						<li><a href="#enqueue_style">wpda_enqueue_style()</a></li>
					</ul>
				</li>
				<li>
					<a href="">Other</a>
					<ul>
						<li><a href="#is_active_theme_name">wpda_is_active_theme_name()</a></li>
					</ul>
				</li>
			</ul>
			<div>
				<h2>Path</h2>
				<section>
					<h3 id="join">wpda_join()</h3>
					<pre><code>
引数として与えられたパスを結合
最後の引数のみオプションとして連想配列を渡せる
@version 0.0.1

@var mixed[] ...$opt (optional) {
	 @var boolean 'last_slash' パスの最後に/をつける
}
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_src">wpda_get_src( $param )</h3>
					<pre><code>
指定されたパスかurlを返す
@version 0.0.1

@var mixed[] $param {
	@var boolean 'uri'   uriで出力する。falseの場合はパスを返す
	@var string  'where' 検索先を指定。"theme"か"user"
	@var string  'blog'  検索するブログディレクトリを指定。特殊なものとしてactive(利用中のテーマ), root(親ブログのテーマ)が利用可能
	@var string  'path'  検索先からの相対パス
}
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="src">wpda_src( $param )</h3>
					<pre><code>
指定されたパスかurlを出力
@version 0.0.1
@see wpda_get_src()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_theme_url">wpda_get_theme_url( $blog='active' )</h3>
					<pre><code>
テーマのURLを返す
@version 0.0.1

@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="theme_url">wpda_theme_url( $blog='active' )</h3>
					<pre><code>
テーマのURLを出力
@version 0.0.1
@see wpda_get_theme_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_theme_path">wpda_get_theme_path( $blog='active' )</h3>
					<pre><code>
テーマのパスを返す
@version 0.0.1

@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="theme_path">wpda_theme_path( $blog='active' )</h3>
					<pre><code>
テーマへのパスを出力
@version 0.0.1

@see wpda_get_theme_path()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_user_url">wpda_get_user_url( $blog='active' )</h3>
					<pre><code>
ユーザーディレクトリのURLを返す
@version 0.0.1

@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="user_url">wpda_user_url( $blog='active' )</h3>
					<pre><code>
ユーザーディレクトリのURLを出力
@version 0.0.1
@see wpda_get_user_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_user_path">wpda_get_user_path( $blog='active' )</h3>
					<pre><code>
ユーザーディレクトリのパスを返す
@version 0.0.1

@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="user_path">wpda_user_path( $blog='active' )</h3>
					<pre><code>
ユーザーディレクトリのパスを出力
@version 0.0.1

@see wpda_get_user_path()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page">wpda_get_page( $dir_name, $url, $from, $path='' )</h3>
					<pre><code>
ページ用素材のパスかurlを返す
@version 0.0.1

@var    string  $dir_name ディレクトリ名
@var    boolean $url      trueでurl falseでパス
@var    string  $from     検索先の指定"theme"か"user"
@var    string  $path     検索先からのパス
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="page">wpda_page( $dir_name, $url, $from, $path='' )</h3>
					<pre><code>
ページ用素材のパスかurlを出力する
@version 0.0.1

@see wpda_get_page()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_img_url">wpda_get_page_img_url( $from, $path='' )</h3>
					<pre><code>
ページ用画像のurlを返す
@version 0.0.1

@var    string $from 検索先の指定"theme"か"user"
@var    string $path 検索先からのパス
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="page_img_url">wpda_page_img_url( $from, $path='' )</h3>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_img_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_css_url">wpda_get_page_css_url( $from, $path='' )</h3>
					<pre><code>
ページ用CSSのurlを返す
@version 0.0.1

@var    string $from 検索先の指定"theme"か"user"
@var    string $path 検索先からのパス
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="page_css_url">wpda_page_css_url( $from, $path='' )</h3>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_css_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_js_url">wpda_get_page_js_url( $from, $path = '' )</h3>
					<pre><code>
ページ用JSのurlを返す
@version 0.0.1

@var    string $from 検索先の指定"theme"か"user"
@var    string $path 検索先からのパス
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="page_js_url">wpda_page_js_url( $from, $path = '' )</h3>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_js_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_php_path">wpda_get_page_php_path( $from, $path = '' )</h3>
					<pre><code>
ページ用PHPのurlを返す
@version 0.0.1

@var    string $from 検索先の指定"theme"か"user"
@var    string $path 検索先からのパス
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="page_php_path">wpda_page_php_path( $from, $path = '' )</h3>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_php_path()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_blog_url">wpda_get_blog_url( $blog_name='active' )</h3>
					<pre><code>
ブログのurlを返す
@version 0.0.1

@var    string $blog_name
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="blog_url">wpda_blog_url( $blog_name='active' )</h3>
					<pre><code>
ブログのurlを出力する
@version 0.0.1

@see wpda_get_blog_url
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_path">wpda_get_page_path()</h3>
					<pre><code>
ページパスを返す
@version 0.0.1

@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="page_path">wpda_page_path()</h3>
					<pre><code>
ページパスを出力
@see wpda_get_page_path()
					</code></pre>
				</section>
				<?php
				// =============================================================
				// UA
				// =============================================================
				?>
				<h2>UA</h2>
				<section>
					<h3 id="get_ua">wpda_get_ua()</h3>
					<pre><code>
UserAgentを返す
@version 0.0.1

@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_device_name">wpda_get_device_name()</h3>
					<pre><code>
デバイス名を返す
@version 0.0.1

@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_smartphone">wpda_is_smartphone()</h3>
					<pre><code>
スマホ環境かどうかを返す
@version 0.0.1

@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_tablet">wpda_is_tablet()</h3>
					<pre><code>
タブレット環境かどうかを返す
@version 0.0.1

@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_pc">wpda_is_pc()</h3>
					<pre><code>
PC環境かどうかを返す
@version 0.0.1

@return boolean
					</code></pre>
				</section>
				<?php
				// =============================================================
				// Helper
				// =============================================================
				?>
				<h2>Helper</h2>
				<section>
					<h3 id="wpda_path2id">wpda_path2id( $path, $type='page' )</h3>
					<pre><code>
スラッグを渡すとIDを返す

@var    string $path
@var    string $type 投稿タイプ
@return int
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_post_history">wpda_get_post_history( $id, $reverse = true )</h3>
					<pre><code>
引数で指定したページからそのページのルートとなるページまでの情報を配列で返す
デフォルトでは最後に配列を反転させるのでルートページ->指定したページの順の配列になる
@version 0.0.1

@var    int|WP_Post $id
@var    boolean     $reverse 結果を反転させるか
@return array[]|false
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_root_post">wpda_get_root_post( $id )</h3>
					<pre><code>
固定ページや投稿タイプの一番上のページ(ルートとなるページ)のオブジェクトを返す
@version 0.0.1

@param  int|WP_Post $id
@return WP_Post
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_term_history">wpda_get_term_history( $tax, $term, $reverse = true )</h3>
					<pre><code>
引数で指定したタームからそのタームのルートとなるタームまでの情報を配列で返す
デフォルトでは最後に配列を反転させるのでルートターム->指定したタームの順の配列になる
@version 0.0.1

@var    string             $tax
@var    string|int|WP_Term $term
@var    boolean            $reverse 結果を反転させるか
@return array[]|false
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_root_term">wpda_get_root_term( $tax, $term )</h3>
					<pre><code>
タームの一番上の親タームのオブジェクトを返す
@version 0.0.1

@var    string             $tax
@var    string|int|WP_Term $term
@return false
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_tax_ancestor_of">wpda_is_tax_ancestor_of( $descendant, $ancestor, $tax )</h3>
					<pre><code>
タームが他のタームの「先祖」タームであるかどうかをチェック
@version 0.0.1

@var    WP_Term|int|string $descendant 祖先
@var    WP_Term|int|string $ancestor   先祖
@var    string             $tax
@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_post_ancestor_of">wpda_is_post_ancestor_of( $descendant, $ancestor, $type = 'page' )</h3>
					<pre><code>
投稿が他の投稿の「先祖」投稿であるかどうかをチェック
@access public
@version 0.0.1

@var    WP_Post|int|string  $descendant 祖先 stringの場合はパス
@var    WP_Post|int|string  $ancestor   先祖 stringの場合はパス
@var    string              $type       投稿タイプ $descendant,$ancestorにstring型を与えた場合のみ結果に影響
@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_child_post">wpda_is_child_post( $post, $type='page' )</h3>
					<pre><code>
子ページかチェック
@version 0.0.1

@param  WP_Post|int|string $post
@param  string             $type 投稿タイプ $postにstring型を与えた場合のみ結果に影響
@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="has_child_page">wpda_has_child_page( $post, $type )</h3>
					<pre><code>
子ページを持っているかチェック
@version 0.0.1

@param WP_Post|int|string $post
@param string             $type
@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="enqueue_style">wpda_enqueue_style( $handle, $src, $deps=false, $ver=false, $media='all' )</h3>
					<pre><code>
wp_enque_styleのラッパー　メディアクエリの設定を簡単にする
@version 0.0.1

@param  string           $handle ハンドル名
@param  string           $src    パス
@param  string[]|boolean $deps   依存ファイル(ハンドル名で指定)
@param  string|boolean   $ver    バージョン
@param  string           $media  メディアクエリ デフォルトはall
@return void
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="enqueue_style">wpda_is_active_theme_name( $name )</h3>
					<pre><code>
アクティブになっているテーマの名前が引数のものか
@version 0.1.0

@var    string  $name
@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
			</div>
		</div>
	</div>
	<p class="copy-right">(C) 2017- WITHPROJECTS inc.</p>
</div>
