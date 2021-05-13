<?php b4st_footer_before();?>
<?php

$directions_url = trim(get_field('directions_url', 'option'));
$phone_number = trim(get_field('phone', 'option'));
$phonestrip = str_replace(array(' ', '-', ')', '(', '.'), '' , $phone_number);
$footer_logo_left = get_field( 'footer_logo_left', 'option' );
?>
<footer id="site-footer" itemid="#suborganization" class="bkgd--dkblue" itemscope itemtype="http://schema.org/subOrganization">
<meta itemprop="parentOrganization" content="<?php echo esc_url('https://www.torprops.com/'); ?>" />
<meta itemprop="name" content="<?php esc_html_e('Campus Flats'); ?>" />
<meta itemprop="url" content="<?php echo esc_url('http://localhost:8888/campusflats/'); ?>" />
  <div class="container-xl">
    <div class="row justify-content-lg-between align-items-lg-center">
      <div class="col-xl-auto footercfLogoCol">
        <?php if ( $footer_logo_left ) { ?>
	         <?php echo wp_get_attachment_image( $footer_logo_left, 'full', false, array( "class" => "img-fluid footerLogo footerLogo--campusFlats" )); ?>
         <?php } ?>
      </div>

      <div class="col-xl-auto text-center text-lg-left">
        <h3 class="h3"><?php esc_html_e('Contact'); ?></h3>

<?php if ( have_rows( 'general_contact', 'option' ) ) : ?>
	<?php while ( have_rows( 'general_contact', 'option' ) ) : the_row(); ?>
	<?php	$contact_name = trim(get_sub_field('contact_name')); $contact_email = trim(get_sub_field('contact_email'));
    $contact_phone_field = trim(get_sub_field('contact_phone'));
if ($contact_phone_field) {
  $show_contact_phone = $contact_phone_field;
  $contact_phone_url = str_replace(array(' ', '-', ')', '(', '.'), '' , $contact_phone_field);
} else {
  $show_contact_phone = $phone_number;
  $contact_phone_url = $phonestrip;
}
		 ?>
<span class="d-block"><?php echo $contact_name; ?></span>
<a href="<?php echo esc_url('tel:'. $contact_phone_url); ?>" class="d-inline-block"><span itemprop="phone"><?php echo $show_contact_phone; ?></span></a><br>
<a href="<?php echo esc_url('mailto:'. $contact_email); ?>" class="d-inline-block"><span itemprop="email"><?php echo $contact_email; ?></span></a>
	<?php endwhile; ?>
<?php endif; ?>
      </div>

      <div class="col-xl-auto text-center text-lg-left">
        <h3 class="h3"><?php esc_html_e('Main Office'); ?></h3>
        <?php if ( have_rows( 'main_address', 'option' ) ) : ?>
          <address class="text-lg-left mb-0" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        	<?php while ( have_rows( 'main_address', 'option' ) ) : the_row(); ?>
        		<?php $street_address = trim(get_sub_field('street_address')); $city = trim(get_sub_field('city')); $state = trim(get_sub_field('state')); $zip = trim(get_sub_field('zip')); ?>
              <span itemprop="streetAddress"><?php echo $street_address; ?></span><br>
              <span itemprop="addressLocality"><?php echo $city; ?></span>, <span itemprop="addressRegion"><?php echo $state; ?></span> <span itemprop="postalCode"><?php echo $zip; ?></span><br>
              <span class="telephone"><a href="<?php echo esc_url('tel:'. $phonestrip); ?>"><span itemprop="telephone"><?php echo $phone_number; ?></span></a></span><br>
              <meta itemprop="addressCountry" content="US" />
        	<?php endwhile; ?>
          </address>
        <?php endif; ?>
      </div>

      <div class="col-xl-auto text-center text-lg-left">
        <h3 class="h3"><?php esc_html_e('Follow Us'); ?></h3>
<?php if ( have_rows( 'social_media', 'option' ) ) : ?>
<ul class="mb-0">
<?php while ( have_rows( 'social_media', 'option' ) ) : the_row(); ?>
<?php $social_media_name = esc_html(trim(get_sub_field('social_media_name'))); $social_media_url = trim(get_sub_field('social_media_url')); ?>
<?php if(!empty($social_media_name) && !empty($social_media_url)) { ?>
<li><a target="_blank" rel="noopener" href="<?php echo $social_media_url; ?>"><?php echo $social_media_name; ?></a></li>
<?php } ?>
<?php endwhile; ?>
</ul>
<?php else : ?>
	<?php // no rows found ?>
<?php endif; ?>
      </div>

      <div class="col-xl-auto footertpLogoCol">
        <?php if ( have_rows( 'footer_logo_right', 'option' ) ) : ?>
        	<?php while ( have_rows( 'footer_logo_right', 'option' ) ) : the_row(); ?>
        		<?php $image = get_sub_field( 'image' ); $footer_logo_right_url = trim(get_sub_field('footer_logo_right_url')); ?>
        		<?php if ( $image ) { ?>
              <?php if($footer_logo_right_url) { ?>
                <a target="_blank" rel="noopener" href="<?php echo $footer_logo_right_url; ?>">
                  <?php echo wp_get_attachment_image( $image, 'full', false, array( "class" => "img-fluid footerLogo footerLogo--torProps" )); ?>
                </a>
              <?php } else { ?>
                <?php echo wp_get_attachment_image( $image, 'full', false, array( "class" => "img-fluid footerLogo footerLogo--torProps" )); ?>
              <?php } ?>
        		<?php } ?>
        	<?php endwhile; ?>
        <?php endif; ?>
      </div>


    </div>
  </div>
</footer>
<?php b4st_footer_after();?>
<?php b4st_bottomline();?>
<?php wp_footer(); ?>
</body>
</html>
