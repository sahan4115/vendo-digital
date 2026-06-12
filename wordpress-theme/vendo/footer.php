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
        <a href="https://www.instagram.com/vendo_digital/" data-line>Instagram</a>
        <a href="#" data-line>Facebook</a>
        <a href="https://uk.linkedin.com/company/vendo-digital-ltd" data-line>LinkedIn</a>
        <a href="#" data-line>YouTube</a>
      </div>
      <div class="f-col">
        <span class="f-head">Office</span>
        <span class="f-text">5 Sandiford Road<br/>Sutton, Surrey SM3 9RN</span>
        <a href="mailto:hello@vendodigital.co.uk" data-line>hello@vendodigital.co.uk</a>
        <a href="tel:02071014967" data-line>0207 101 4967</a>
      </div>
    </div>
    <div class="footer-bar">
      <span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Vendo Digital Ltd.</span>
      <span>www.vendodigital.co.uk</span>
      <button class="to-top" id="toTop" data-magnetic>Back to top &uarr;</button>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>
