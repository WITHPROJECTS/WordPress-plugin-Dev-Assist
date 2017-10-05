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
	$opt['break_point']          = $_POST['wpda_break_point'];                                  // ブレイクポイント
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
	$opt['file_permision_alert'] = isset( $_POST['wpda_file_permision_alert'] ) ? true : false; // 適切なファイル権限では無い場合アラート

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
			</ul>
			<div>
				<h2>Path</h2>
				<section>
					<h3 id="join">wpda_join()</h2>
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
					<h3 id="get_src">wpda_get_src( $param )</h2>
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
					<h3 id="src">wpda_src( $param )</h2>
					<pre><code>
指定されたパスかurlを出力
@version 0.0.1
@see wpda_get_src()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_theme_url">wpda_get_theme_url( $path='', $blog='active' )</h2>
					<pre><code>
テーマのURLを返す
@version 0.0.1

@var    string $path
@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="theme_url">wpda_theme_url( $path='', $blog='active' )</h2>
					<pre><code>
テーマのURLを出力
@version 0.0.1
@see wpda_get_theme_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_theme_path">wpda_get_theme_path( $path='', $blog='active' )</h2>
					<pre><code>
テーマのパスを返す
@version 0.0.1

@var    string $path
@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="theme_path">wpda_theme_path( $path='', $blog='active' )</h2>
					<pre><code>
テーマへのパスを出力
@version 0.0.1

@see wpda_get_theme_path()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_user_url">wpda_get_user_url( $path='', $blog='active' )</h2>
					<pre><code>
ユーザーディレクトリのURLを返す
@version 0.0.1

@var    string $path
@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="user_url">wpda_user_url( $path='',  $blog='active' )</h2>
					<pre><code>
ユーザーディレクトリのURLを出力
@version 0.0.1
@see wpda_get_user_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_user_path">wpda_get_user_path( $path='',  $blog='active' )</h2>
					<pre><code>
ユーザーディレクトリのパスを返す
@version 0.0.1

@var    string $path
@var    string $blog
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="user_path">wpda_user_path( $path='', $blog='active' )</h2>
					<pre><code>
ユーザーディレクトリのパスを出力
@version 0.0.1

@see wpda_get_user_path()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page">wpda_get_page( $dir_name, $url, $from, $path='' )</h2>
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
					<h3 id="page">wpda_page( $dir_name, $url, $from, $path='' )</h2>
					<pre><code>
ページ用素材のパスかurlを出力する
@version 0.0.1

@see wpda_get_page()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_img_url">wpda_get_page_img_url( $from, $path='' )</h2>
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
					<h3 id="page_img_url">wpda_page_img_url( $from, $path='' )</h2>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_img_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_css_url">wpda_get_page_css_url( $from, $path='' )</h2>
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
					<h3 id="page_css_url">wpda_page_css_url( $from, $path='' )</h2>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_css_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_js_url">wpda_get_page_js_url( $from, $path = '' )</h2>
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
					<h3 id="page_js_url">wpda_page_js_url( $from, $path = '' )</h2>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_js_url()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_php_path">wpda_get_page_php_path( $from, $path = '' )</h2>
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
					<h3 id="page_php_path">wpda_page_php_path( $from, $path = '' )</h2>
					<pre><code>
ページ用画像のurlを出力する
@version 0.0.1

@see wpda_get_page_php_path()
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_blog_url">wpda_get_blog_url( $blog_name='active' )</h2>
					<pre><code>
ブログのurlを返す
@version 0.0.1

@var    string $blog_name
@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="blog_url">wpda_blog_url( $blog_name='active' )</h2>
					<pre><code>
ブログのurlを出力する
@version 0.0.1

@see wpda_get_blog_url
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_page_path">wpda_get_page_path()</h2>
					<pre><code>
ページパスを返す
@version 0.0.1

@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="page_path">wpda_page_path()</h2>
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
					<h3 id="get_ua">wpda_get_ua()</h2>
					<pre><code>
UserAgentを返す
@version 0.0.1

@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="get_device_name">wpda_get_device_name()</h2>
					<pre><code>
デバイス名を返す
@version 0.0.1

@return string
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_smartphone">wpda_is_smartphone()</h2>
					<pre><code>
スマホ環境かどうかを返す
@version 0.0.1

@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_tablet">wpda_is_tablet()</h2>
					<pre><code>
タブレット環境かどうかを返す
@version 0.0.1

@return boolean
					</code></pre>
				</section>
				<?php // ---------------------------------------------------- ?>
				<section>
					<h3 id="is_pc">wpda_is_pc()</h2>
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
				<section>
					<h3 id="is_pc">wpda_slug2id( $slug )</h2>
					<pre><code>
PC環境かどうかを返す
@version 0.0.1

@return boolean
					</code></pre>
				</section>
			</div>
		</div>
	</div>
	<p class="copy-right">(C) 2017- WITHPROJECTS inc.</p>
</div>
