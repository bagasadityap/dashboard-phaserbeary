<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan 360</title>
    <script src="https://aframe.io/releases/1.2.0/aframe.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    {{-- <style>
        body {
            font-family: 'Poppins';font-size: 22px;
        }
    </style>
    <style>
        body { margin: 0; }
        a-scene { height: 100vh; }
        .button {
            font-size: 24px;
            font-weight: 500;
            color: white;
        }
    </style> --}}

    <script>
        AFRAME.registerComponent('clickable', {
            init: function () {
                this.el.addEventListener('click', function () {
                    window.location.href = 'music_room.html';
                });
            }
        });
    </script>
</head>
<body>
    <a-scene>
        <a-sky src="{{ asset('storage/' . $model) }}" rotation="0 0 0"></a-sky>

        <a-entity camera look-controls position="0 1.6 0">
            <a-entity cursor="fuse: false"
                      geometry="primitive: ring; radiusInner: 0.02; radiusOuter: 0.03"
                      material="color: #FFFFFF; shader: flat"
                      position="0 0 -1"
                      raycaster="objects: .button">
            </a-entity>
        </a-entity>

    </a-scene>
</body>
</html>
