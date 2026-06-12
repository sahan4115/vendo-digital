<?php
/**
 * Footer: site footer + wp_footer().
 *
 * The GSAP, ScrollTrigger, Three.js and main.js scripts are enqueued in
 * functions.php (in the footer, in dependency order), so wp_footer()
 * prints them here — no manual <script> tags needed.
 *
 * @package Vendo
 */
?>

  <!-- ════════ FOOTER ════════ -->
  <footer class="footer">
    <div class="footer-top">
      <span class="footer-logo" aria-hidden="true">Vendo<span class="dot">.</span></span>
    </div>
    <div class="footer-grid">
      <div class="f-col">
        <span class="f-head">Sitemap</span>
        <a href="#work" data-line>Results</a>
        <a href="#services" data-line>Services</a>
        <a href="#niches" data-line>Dental &amp; E-commerce</a>
        <a href="#contact" data-line>Free audit</a>
      </div>
      <div class="f-col">
        <span class="f-head">Social</span>
        <a href="<?php echo esc_url( vendo_mod( 'ig' ) ); ?>" data-line>Instagram</a>
        <a href="<?php echo esc_url( vendo_mod( 'fb' ) ); ?>" data-line>Facebook</a>
        <a href="<?php echo esc_url( vendo_mod( 'li' ) ); ?>" data-line>LinkedIn</a>
        <a href="<?php echo esc_url( vendo_mod( 'yt' ) ); ?>" data-line>YouTube</a>
      </div>
      <div class="f-col">
        <span class="f-head">Office</span>
        <span class="f-text"><?php vendo_the( 'addr1' ); ?><br/><?php vendo_the( 'addr2' ); ?></span>
        <a href="mailto:<?php echo esc_attr( vendo_mod( 'email' ) ); ?>" data-line><?php vendo_the( 'email' ); ?></a>
        <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', vendo_mod( 'phone' ) ) ); ?>" data-line><?php vendo_the( 'phone' ); ?></a>
      </div>
    </div>
    <div class="footer-bar">
      <span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php vendo_the( 'copyright' ); ?></span>
      <span><?php vendo_the( 'site_url_label' ); ?></span>
      <button class="to-top" id="toTop" data-magnetic>Back to top &uarr;</button>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>
