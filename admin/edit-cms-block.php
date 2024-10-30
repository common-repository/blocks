<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function wpcmsb_admin_save_button( $post_id ) {
	static $button = '';

	if ( ! empty( $button ) ) {
		echo $button;
		return;
	}

	$nonce = wp_create_nonce( 'wpcmsb-save-cms-block_' . $post_id );

	$onclick = sprintf(
		"this.form._wpnonce.value = '%s';"
		. " this.form.action.value = 'save';"
		. " return true;",
		$nonce );

	$button = sprintf(
		'<input type="submit" class="button-primary" name="wpcmsb-save" value="%1$s" onclick="%2$s" />',
		esc_attr( __( 'Save', 'cms-block' ) ),
		$onclick );

	echo $button;
}

?><div class="wrap">

<h2><?php
	if ( $post->initial() ) {
		echo esc_html( __( 'Add New Block', 'cms-block' ) );
	} else {
		echo esc_html( __( 'Edit Block', 'cms-block' ) );

		if ( current_user_can( 'wpcmsb_edit_cms_blocks' ) ) {
			echo ' <a href="' . esc_url( menu_page_url( 'block-new', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Add New', 'cms-block' ) ) . '</a>';
		}
	}
?></h2>

<?php do_action( 'wpcmsb_admin_notices' ); ?>

<?php
if ( $post ) :

	if ( current_user_can( 'wpcmsb_edit_cms_block', $post_id ) ) {
		$disabled = '';
	} else {
		$disabled = ' disabled="disabled"';
	}
?>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'block', false ) ) ); ?>" id="wpcmsb-admin-form-element"<?php do_action( 'wpcmsb_post_edit_form_tag' ); ?>>
<?php
	if ( current_user_can( 'wpcmsb_edit_cms_block', $post_id ) ) {
		wp_nonce_field( 'wpcmsb-save-cms-block_' . $post_id );
	}
?>
<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
<input type="hidden" id="wpcmsb-locale" name="wpcmsb-locale" value="<?php echo esc_attr( $post->locale ); ?>" />
<input type="hidden" id="hiddenaction" name="action" value="save" />
<input type="hidden" id="active-tab" name="active-tab" value="<?php echo isset( $_GET['active-tab'] ) ? (int) $_GET['active-tab'] : '0'; ?>" />

<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">

<div id="post-body-content">
  <div id="titlediv">
  <div id="titlewrap">
  	<label class="screen-reader-text" id="title-prompt-text" for="title"><?php echo esc_html( __( 'Enter title here', 'cms-block' ) ); ?></label>
  <?php
  	$posttitle_atts = array(
  		'type' => 'text',
  		'name' => 'post_title',
  		'size' => 30,
  		'value' => $post->initial() ? '' : $post->title(),
  		'id' => 'title',
  		'spellcheck' => 'true',
  		'autocomplete' => 'off',
  		'disabled' => current_user_can( 'wpcmsb_edit_cms_block', $post_id )
  			? '' : 'disabled' );

  	echo sprintf( '<input %s />', wpcmsb_format_atts( $posttitle_atts ) );
  ?>
  <?php

    $wsbenvolver=$post->prop('wsbenvolver'); //aqui captura el check de envolver
    $wsbtipoenvol=$post->prop('wsbtipoenvol'); //aqui captura el tipo de envoltura
    $wsbclaseenvol=$post->prop('wsbclaseenvol'); //aqui coloca la clase de la envoltura
    $wsbautop=$post->prop('wsbautop'); //aqui coloca la clase de la envoltura
    $wsbtabvisual=$post->prop('wsbtabvisual'); //aqui coloca la clase de la envoltura
  ?>


  </div><!-- #titlewrap -->
  <div class="inside">

  <?php
  	if ( ! $post->initial() ) :
  ?>
  	<p class="description">
    <label for="wpcmsb-shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", 'cms-block' ) ); ?></label>
  	<span class="shortcode wp-ui-highlight"><input type="text" id="wpcmsb-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $post->shortcode() ); ?>" /></span>
  	</p>

  <?php
  	endif;
  ?>
  </div>
  </div><!-- #titlediv -->

  <div id="cms-block-editor">

    <?php

      $args = array(
        'textarea_rows' => 6,
        'teeny' => false,
        'tinymce' => $wsbtabvisual,
        'quicktags' => true,
        'wpautop' =>  true

      );
      wp_editor(htmlspecialchars_decode(stripslashes($post->prop('form'))), 'wpcmsb-form', $args );

    ?>

  </div><!-- #cms-block-editor -->

</div><!-- #post-body-content -->

<div id="postbox-container-1" class="postbox-container">
  <div id="side-sortables" class="meta-box-sortables">

    <?php if ( current_user_can( 'wpcmsb_edit_cms_block', $post_id ) ) : ?>
    <div id="submitdiv" class="postbox">

      <h2 class="hndle ui-sortable-handle"><span><?php echo esc_html( __( 'Status', 'cms-block' ) ); ?></span></h2>

      <div class="inside">
          <div class="submitbox" id="submitpost">

          <div id="minor-publishing-actions">

            <div class="hidden">
            	<input type="submit" class="button-primary" name="wpcmsb-save" value="<?php echo esc_attr( __( 'Save', 'cms-block' ) ); ?>" />
            </div>

            <?php
            	if ( ! $post->initial() ) :
            		$copy_nonce = wp_create_nonce( 'wpcmsb-copy-cms-block_' . $post_id );
            ?>
            	<input type="submit" name="wpcmsb-copy" class="copy button" value="<?php echo esc_attr( __( 'Duplicate', 'cms-block' ) ); ?>" <?php echo "onclick=\"this.form._wpnonce.value = '$copy_nonce'; this.form.action.value = 'copy'; return true;\""; ?> />
            <?php endif; ?>

          </div><!-- #minor-publishing-actions -->

          <div id="major-publishing-actions">

          <?php
          	if ( ! $post->initial() ) :
          		$delete_nonce = wp_create_nonce( 'wpcmsb-delete-cms-block_' . $post_id );
          ?>
          <div id="delete-action">
          	<input type="submit" name="wpcmsb-delete" class="delete submitdelete" value="<?php echo esc_attr( __( 'Delete', 'cms-block' ) ); ?>" <?php echo "onclick=\"if (confirm('" . esc_js( __( "You are about to delete this Block.\n  'Cancel' to stop, 'OK' to delete.", 'cms-block' ) ) . "')) {this.form._wpnonce.value = '$delete_nonce'; this.form.action.value = 'delete'; return true;} return false;\""; ?> />
          </div><!-- #delete-action -->
          <?php endif; ?>

          <div class="save-cms-block textright">
          	<?php wpcmsb_admin_save_button( $post_id ); ?>
          </div>
          </div><!-- #major-publishing-actions -->


          </div><!-- #submitpost -->
      </div>

    </div><!-- #submitdiv -->
    <?php endif; ?>

    <div id="information" class="postbox bsponsors">
      <h2 class="hndle ui-sortable-handle"><span><?php echo esc_html( __( 'Information', 'cms-block' ) ); ?></span></h2>
      <div class="inside">
        <h3 class="textcenter"><a class="blks-link" href=" https://goo.gl/XhPq1N" target="_blank">Do your best work, here!</a></h3>
        <a href=" https://goo.gl/XhPq1N" target="_blank"><img src="<?php echo wpcmsb_plugin_url() ?>/admin/images/gsuite.png"></a>
<!--         <ul>
        <li><?php echo wpcmsb_link( __( '//renzojohnson.com/support/', 'cms-block' ), __( 'Support', 'cms-block' ) ); ?></li>
        </ul> -->
      </div>
    </div><!-- #information -->


    <div id="sponsors" class="postbox bsponsors">
      <h2 class="hndle ui-sortable-handle"><span><?php echo esc_html( __( 'Recommended Products', 'cms-block' ) ); ?></span></h2>
      <div class="inside">
        <h3 class="textcenter"><a class="blks-link" href="http://bit.ly/2B3OWOO" target="_blank">Builde your online business</a></h3>
        <a href="http://bit.ly/2B3OWOO" target="_blank"><img src="<?php echo wpcmsb_plugin_url() ?>/admin/images/woocommerce.png"></a>
      </div>
    </div><!-- #information -->



  </div><!-- #side-sortables -->
</div><!-- #postbox-container-1 -->



<div id="postbox-container-2" class="postbox-container">

  <div id="normal-sortables" class="meta-box-sortables">

    <div class="postbox">

      <h2 class="hndle ui-sortable-handle">Additional Settings</h2>

      <div class="inside">

        <p>These are additional settings for your block. You can choose to wrap its contents with an HTML tag and add CSS classes to it.</p>

        <table class="form-table">
          <tbody>
            <tr>
              <th scope="row">Block wrap</th>
              <td>
                <fieldset><legend class="screen-reader-text"><span>Block wrap</span></legend><label for="wpcmsb-wsbenvolver">
                <input type="checkbox" id="wpcmsb-wsbenvolver" name="wpcmsb-wsbenvolver" value="1"<?php echo (isset($wsbenvolver) && $wsbenvolver==1) ? ' checked="checked"' : ''; ?> />
                <?php echo esc_html( __( 'Wrap', 'wpcmsb' ) ); ?> </label>
                </fieldset>
              </td>
            </tr>

            <tr>
              <th scope="row"><label for="default_role">Wrap tag</label></th>
              <td>
                <select id="wpcmsb-wsbtipoenvol" name="wpcmsb-wsbtipoenvol">
                  <option value="1" <?php echo (isset($wsbtipoenvol) && $wsbtipoenvol==1) ? ' selected' : ''; ?> >&lt;div&gt; </option>
                  <option value="2" <?php echo (isset($wsbtipoenvol) && $wsbtipoenvol==2) ? ' selected' : ''; ?> >&lt;p&gt; </option>
                  <option value="3" <?php echo (isset($wsbtipoenvol) && $wsbtipoenvol==3) ? ' selected' : ''; ?> >&lt;span&gt;</option>
                </select>
                <span id="utc-time" for="wpcmsb-envolver"><?php echo esc_html( __( 'Choose the HTML tag for your content: &lt;div&gt;, &lt;p&gt; or &lt;span&gt; ', 'wpcmsb' ) ); ?> </span>

              </td>
            </tr>

            <tr>
              <th scope="row"><label for="blogdescription">Wrap classes</label></th>
              <td>
                <input type="text" id="wpcmsb-wsbclaseenvol" name="wpcmsb-wsbclaseenvol" <?php echo (isset($wsbclaseenvol)) ? 'value="' . $wsbclaseenvol . '"' : 'value=""'; ?> />
                <p class="description" id="tagline-description">Additional classes you want to add to the block wrapper.</p>
              </td>
            </tr>

            <tr>
              <th scope="row">WPautop</th>
              <td>
                <fieldset><legend class="screen-reader-text"><span>Block wrap</span></legend><label for="wpcmsb-wsbautop">
                <input type="checkbox" id="wpcmsb-wsbautop" name="wpcmsb-wsbautop" value="1"<?php echo (isset($wsbautop) && $wsbautop==1) ? ' checked="checked"' : ''; ?> />
                <?php echo esc_html( __( 'Makes double line-breaks in the text into HTML paragraphs (&lt;p&gt;...&lt;/p&gt;)', 'wpcmsb' ) ); ?> </label>
                </fieldset>
              </td>
            </tr>


            <tr>
              <th scope="row">Active Visual Tab</th>
              <td>
                <fieldset><legend class="screen-reader-text"><span>Visual Tab</span></legend><label for="wpcmsb-wsbtabvisual">
                <input type="checkbox" id="wpcmsb-wsbtabvisual" name="wpcmsb-wsbtabvisual" value="1"<?php echo (isset($wsbtabvisual) && $wsbtabvisual==1) ? ' checked="checked"' : ''; ?> />
                <?php echo esc_html( __( 'Activates Tiny MCE for this block', 'wpcmsb' ) ); ?> </label>
                </fieldset>
              </td>
            </tr>


          </tbody>
        </table>

          <div class="textright"><?php if ( current_user_can( 'wpcmsb_edit_cms_block', $post_id ) ) : ?>
            <p class="submits"><?php wpcmsb_admin_save_button( $post_id ); ?></p>
          <?php endif; ?></div>

      </div>

    </div><!-- .postbox -->

  </div><!-- #normal-sortables -->

</div><!-- #postbox-container-2 -->



</div><!-- #post-body -->
<br class="clear" />
</div><!-- #poststuff -->
</form>

<?php endif; ?>

</div><!-- .wrap -->

<?php

	do_action( 'wpcmsb_admin_footer', $post );
