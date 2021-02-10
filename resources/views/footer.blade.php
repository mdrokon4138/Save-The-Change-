<link href="{{ asset('assets/css/footer.css') }}" rel="stylesheet">

<footer class="footer-area footer--light" id="footer">
  <div class="footer-big">
    <!-- start .container -->
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-12">
          <div class="footer-widget">
            <div class="widget-about">
              <img src="{{ asset('/images/'. $web->logo)}}" style="height: 80px;" alt="" class="img-fluid">
              <ul class="contact-details">
                <li>
                  <span class="icon-earphones"></span> Call Us:
                  <a href="tel:{{ $web->phone }}">{{ $web->phone }}</a>
                </li>
                <li>
                  <span class="icon-envelope-open"></span>
                  <a href="mailto:{{ $web->email }}">{{ $web->email }}</a>
                </li>
              </ul>
            </div>
          </div>
          <!-- Ends: .footer-widget -->
        </div>
        <!-- end /.col-md-4 -->
        <div class="col-md-3 col-sm-4">
          <div class="footer-widget">
            <div class="footer-menu footer-menu--1">
              <h4 class="footer-widget-title"></h4>
              <ul>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
              </ul>
            </div>
            <!-- end /.footer-menu -->
          </div>
          <!-- Ends: .footer-widget -->
        </div>
        <!-- end /.col-md-3 -->

        <div class="col-md-3 col-sm-4">
          <div class="footer-widget">
            <div class="footer-menu">
              <h4 class="footer-widget-title"></h4>
              <ul>
                <li>
                  <a href="#"</a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
              </ul>
            </div>
            <!-- end /.footer-menu -->
          </div>
          <!-- Ends: .footer-widget -->
        </div>
        <!-- end /.col-lg-3 -->

        <div class="col-md-3 col-sm-4">
          <div class="footer-widget">
            <div class="footer-menu no-padding">
              <h4 class="footer-widget-title">Help Support</h4>
              <ul>
                <li>
                  <a href="{{ url('contact-us')}}">Contact Us </a>
                </li>
                <li>
                  <a href="{{ url('terms-condition')}}">Terms &amp; Conditions</a>
                </li>
                <li>
                  <a href="{{ url('refund-policy')}}">Refund Policy</a>
                </li>
                <li>
                  <a href="{{ url('faq-page') }}">FAQs</a>
                </li>
              </ul>
            </div>
            <!-- end /.footer-menu -->
          </div>
          <!-- Ends: .footer-widget -->
        </div>
        <!-- Ends: .col-lg-3 -->

      </div>
      <!-- end /.row -->
    </div>
    <!-- end /.container -->
  </div>
  <!-- end /.footer-big -->

  <div class="mini-footer">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="copyright-text">
            <p>© <?php echo date("Y"); ?>
              <a href="#">SaveDchange</a>. All rights reserved.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>