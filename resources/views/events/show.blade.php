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
                padding: 100,
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

            var x = schemeObject.getX() * schemeDesigner.getWidth() - 570; // Масштабування по ширині схеми
            var y = schemeObject.getY() * schemeDesigner.getHeight() - 251; // Масштабування по висоті схеми
            var width = schemeObject.getWidth() * schemeDesigner.getWidth();
            var height = schemeObject.getHeight() * schemeDesigner.getHeight();

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
            var scaleX = schemeDesigner.getWidth() / canvas.width; // Масштабування по X
            var scaleY = schemeDesigner.getHeight() / canvas.height; // Масштабування по Y
            var clickX = (event.clientX - rect.left) * scaleX; 
            var clickY = (event.clientY - rect.top) * scaleY;

            defaultLayer.getObjects().forEach(function(schemeObject) {
                var objX = schemeObject.getX() * schemeDesigner.getWidth();
                var objY = schemeObject.getY() * schemeDesigner.getHeight();
                var objWidth = schemeObject.getWidth() * schemeDesigner.getWidth();
                var objHeight = schemeObject.getHeight() * schemeDesigner.getHeight();

                console.log('Canvas width/height:', schemeDesigner.getWidth(), schemeDesigner.getHeight());
                console.log('SchemeObject coords:', objX, objY, objWidth, objHeight);

                // Перевірка координат кліку
                if (clickX >= objX && clickX <= objX + objWidth && clickY >= objY && clickY <= objY + objHeight) {
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