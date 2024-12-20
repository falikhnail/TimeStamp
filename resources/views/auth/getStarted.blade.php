
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
        
        <title>index</title>

        <!-- Favicon and Touch Icons  -->
        <link rel="shortcut icon" href="{{ url('/myhr/images/logo.png') }}" />
        <link rel="apple-touch-icon-precomposed" href="{{ url('/myhr/images/logo.png') }}" />
        <!-- Font -->
        <link rel="stylesheet" href="{{ url('/myhr/fonts/fonts.css') }}" />
        <!-- Icons -->
        <link rel="stylesheet" href="{{ url('/myhr/fonts/icons-alipay.css') }}">
        <link rel="stylesheet" href="{{ url('/myhr/styles/bootstrap.css') }}">
        <link rel="stylesheet"type="text/css" href="{{ url('/myhr/styles/styles.css') }}"/>
        <link rel="manifest" href="{{ url('/myhr/_manifest.json') }}" data-pwa-version="set_in_manifest_and_pwa_js">
        <link rel="apple-touch-icon" sizes="192x192" href="{{ url('/myhr/app/icons/icon-192x192.png') }}">
        
    </head>
    <body>


        <!-- preloade -->
        <div class="preload preload-container">
            <div class="preload-logo">
              <div class="spinner"></div>
            </div>
          </div>
        <!-- /preload -->
        <div class="boarding-section">
            <div class="tf-container">
                <div class="images">    
                    <img src="{{ url('/myhr/images/boarding/boarding1.png') }}" alt="image">
                </div>
            </div>
        </div>

        <div class="boarding-content mt-7">
            <div class="tf-container">
                <div class="boarding-title">
                    <h1 class="tf-title">Alipay Management In The Easiest Way</h1>
                    <p>Start managing your wallet for a better and more organized future for your life</p>
                </div>
                <a href="{{ url('/') }}" class="tf-btn accent large">Get Started</a>
                <p class="bottom">By creating an account, you’re agree to out <a href="#">Privacy policy</a>  and <a href="#">Term of use</a> </p>

            </div>
        </div>

       
        
        

        <script type="text/javascript" src="{{ url('/myhr/javascript/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/myhr/javascript/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/myhr/javascript/main.js') }}"></script>
        <script type="text/javascript" src="{{ url('/myhr/javascript/init.js') }}"></script>
         
    </body>
    </html>