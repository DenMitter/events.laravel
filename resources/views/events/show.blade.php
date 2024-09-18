@extends('layouts.app')

@section('content')
  <div id="canvas-wrapper" style="position:relative;">
    <canvas id="canvas"></canvas>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        var canvas = document.getElementById('canvas');
        var schemeDesigner = new SchemeDesigner.Scheme(canvas, {
            options: {
                cacheSchemeRatio: 2
            },
            scroll: {
                maxHiddenPart: 0.85
            },
            zoom: {
                padding: 1000,
                maxScale: 1,
                zoomCoefficient: 1.04
            },
            storage: {
                treeDepth: 6
            }
        });

        var defaultLayer = new SchemeDesigner.Layer('default', { zIndex: 1 });

        var places = @json($places);

        function renderPlace(schemeObject, scheme, view) {
            var canvas = scheme.getCanvas();
            var ctx = canvas.getContext('2d');

            var offsetX = -2050;
            var offsetY = -1505;

            var x = schemeObject.getX() * scheme.getWidth() + offsetX;
            var y = schemeObject.getY() * scheme.getHeight() + offsetY;
            var width = schemeObject.getWidth() * scheme.getWidth();
            var height = schemeObject.getHeight() * scheme.getHeight();

            ctx.fillStyle = schemeObject.getParams().is_available ? 'green' : 'red';
            ctx.fillRect(x, y, width, height);
            ctx.strokeStyle = 'black';
            ctx.strokeRect(x, y, width, height);
        }

        places.response.forEach(function(place) {
            var schemeObject = new SchemeDesigner.SchemeObject({
                x: place.x / 100, 
                y: place.y / 100, 
                width: place.width / 100, 
                height: place.height / 100, 
                renderFunction: renderPlace,
                is_available: place.is_available,
                id: place.id
            });

            schemeObject.setClickFunction(function(schemeObject, schemeDesigner, view, e) {
                if (schemeObject.getParams().is_available) {
                    schemeObject.getParams().is_available = false;
                    schemeDesigner.requestRenderAll();
                    alert('Місце заброньовано!');
                } else {
                    alert('Місце вже заброньовано.');
                }
            });

            defaultLayer.addObject(schemeObject);
        });

        schemeDesigner.addLayer(defaultLayer);

        canvas.addEventListener('click', function(event) {
            var rect = canvas.getBoundingClientRect();
            var scaleX = canvas.width / schemeDesigner.getWidth();
            var scaleY = canvas.height / schemeDesigner.getHeight();
            var x = (event.clientX - rect.left) * scaleX;
            var y = (event.clientY - rect.top) * scaleY;

            defaultLayer.getObjects().forEach(function(schemeObject) {
                var sx = schemeObject.getX() * schemeDesigner.getWidth();
                var sy = schemeObject.getY() * schemeDesigner.getHeight();
                var sw = schemeObject.getWidth() * schemeDesigner.getWidth();
                var sh = schemeObject.getHeight() * schemeDesigner.getHeight();
                if (x >= sx && x <= sx + sw && y >= sy && y <= sy + sh) {
                    console.log(schemeObject.getParams());
                    if (schemeObject.getParams().is_available) {
                        schemeObject.getParams().is_available = false;
                        schemeDesigner.requestRenderAll();
                        alert('Місце заброньовано!');
                    } else {
                        alert('Місце вже заброньовано.');
                    }
                }
            });
        });

        schemeDesigner.render();
    });
  </script>
@endsection