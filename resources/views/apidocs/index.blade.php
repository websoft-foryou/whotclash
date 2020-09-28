<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>WhotClash API Documentation</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/apidocs/swagger-ui.css') }}" >
    <style>
      html
      {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }

      *,
      *:before,
      *:after
      {
        box-sizing: inherit;
      }

      body
      {
        margin:0;
        background: #fafafa;
      }
    </style>
  </head>

  <body>
    <div id="swagger-ui"></div>

    <script src="{{ asset('js/apidocs/swagger-ui-bundle.js') }}"> </script>
    <script src="{{ asset('js/apidocs/swagger-ui-standalone-preset.js') }}"> </script>
    <script>
    window.onload = function() {
      // Begin Swagger UI call region
      const ui = SwaggerUIBundle({
        //url: "https://petstore.swagger.io/v2/swagger.json",
		url: "whotclash-apidoc.yaml",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
		presets_config: {
		   SwaggerUIStandalonePreset: {
			   TopbarPlugin: false
		   }
		},
        plugins: [
          SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "StandaloneLayout"
      })
      // End Swagger UI call region

      window.ui = ui
    }
  </script>
  </body>
</html>
