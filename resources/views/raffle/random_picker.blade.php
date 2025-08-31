<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Phaser Beary Raffle</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .raffle-card {
    max-width: 600px;
    width: 100%;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    animation: fadeIn 1s ease-in;
  }
  .raffle-header {
    background: linear-gradient(to right, #343a40, #495057);
    color: #fff;
    text-align: center;
    padding: 20px;
    font-size: 1.5rem;
  }
  .raffle-body {
    background: #fff;
    text-align: center;
    padding: 30px;
  }
  #current-image {
    border-radius: 12px;
    transition: transform 0.2s ease;
  }
  #current-image.picking {
    transform: scale(1.05) rotate(3deg);
  }
  #start-btn {
    min-width: 150px;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px);}
    to { opacity: 1; transform: translateY(0);}
  }
</style>
</head>
<body>

<div class="raffle-card">
  <div class="raffle-header">
    🎲 Phaser Beary Raffle 🎲
  </div>
  <div class="raffle-body">
    <img id="current-image" src="image/beary/hidden-1.png" alt="Picking..." class="img-fluid mb-3" style="max-height: 300px;">
    <h5 id="current-name" class="mb-4">Phaser Beary #1</h5>
    <button id="start-btn" class="btn btn-primary btn-lg">Start Raffle</button>
  </div>
</div>

<!-- Modal Pemenang -->
<div class="modal fade" id="winnerModal" tabindex="-1" aria-labelledby="winnerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header justify-content-center">
        <h5 class="modal-title" id="winnerModalLabel">🎉 Winner 🎉</h5>
      </div>
      <div class="modal-body">
        <img id="winner-image" src="" alt="Winner" class="img-fluid rounded mb-3" style="max-height: 300px;">
        <h4 id="winner-name"></h4>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const images = [
        { src: "{{ asset('image/beary/hidden-1.png') }}", name: 'Phaser Beary #1' },
        { src: "{{ asset('image/beary/hidden-2.png') }}", name: 'Phaser Beary #2' },
        { src: "{{ asset('image/beary/hidden-3.png') }}", name: 'Phaser Beary #3' },
        { src: "{{ asset('image/beary/hidden-4.png') }}", name: 'Phaser Beary #4' },
        { src: "{{ asset('image/beary/hidden-5.png') }}", name: 'Phaser Beary #5' },
        { src: "{{ asset('image/beary/hidden-6.png') }}", name: 'Phaser Beary #6' },
    ];

    const currentImage = document.getElementById('current-image');
    const currentName = document.getElementById('current-name');
    const startBtn = document.getElementById('start-btn');

    const winnerModal = new bootstrap.Modal(document.getElementById('winnerModal'));
    const winnerImage = document.getElementById('winner-image');
    const winnerName = document.getElementById('winner-name');

    startBtn.addEventListener('click', function() {
        startBtn.disabled = true;

        const pickDuration = 10;
        const intervalTime = 150;

        currentImage.classList.add('picking');

        let interval = setInterval(() => {
            const random = images[Math.floor(Math.random() * images.length)];
            currentImage.src = random.src;
            currentName.textContent = random.name;
        }, intervalTime);

        setTimeout(() => {
            clearInterval(interval);
            currentImage.classList.remove('picking');
            const winner = images[Math.floor(Math.random() * images.length)];
            winnerImage.src = winner.src;
            winnerName.textContent = winner.name;
            winnerModal.show();
            startBtn.disabled = false;
        }, pickDuration * 1000);
    });
});
</script>

</body>
</html>
