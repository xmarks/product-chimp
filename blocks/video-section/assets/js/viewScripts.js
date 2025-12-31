document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.video-section-section__video-container').forEach(function (container) {
        const overlay = container.querySelector('.video-section-section__overlay');
        const iframe = container.querySelector('.video-section-section__iframe');
        const playButton = container.querySelector('.video-section-section__play-button');

        if (!playButton || !iframe || !overlay) return;

        const player = new Vimeo.Player(iframe);

        playButton.addEventListener('click', function (e) {
            e.preventDefault();
            overlay.classList.add('is-hidden');
            player.play();
        });
    });
});
