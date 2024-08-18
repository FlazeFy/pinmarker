<?php if (!$is_mobile_device): ?>
<div style="margin-top:10vh;" class="position-relative">
    <div style="width:480px; display:block; bottom:calc(var(--spaceJumbo)*2); left:var(--spaceXLG)" class="position-absolute text-white">
        <h5>PinMarker</h5><hr>
        <p>PinMarker is an apps that store data about marked location on your maps. You can save location and separate it based on category or list. You can collaborate and share your saved location with all people. We also provide stats so you can monitoring your saved location.</p>
    </div>
    <h4 class="text-white position-absolute" style="bottom:var(--spaceLG); left:var(--spaceXLG);">Part Of FlazenApps</h4>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#273036" fill-opacity="1" d="M0,32L360,96L720,256L1080,160L1440,256L1440,320L1080,320L720,320L360,320L0,320Z"></path>
    </svg>
</div>
<?php else: ?>
<footer class="bg-dark text-white text-center py-3 px-2" style="border-radius: var(--roundedXLG) var(--roundedXLG) 0 0;">
    <h4 class="mb-2">PinMarker</h4>
    <p>PinMarker is an apps that store data about marked location on your maps. You can save location and separate it based on category or list. You can collaborate and share your saved location with all people. We also provide stats so you can monitoring your saved location.</p>
    <a class="btn btn-light my-2 rounded-pill"><i class="fa-solid fa-mobile-screen-button"></i> Get your Mobile Apps Here</a>
    <h6 class="mb-0 text-secondary">Part Of FlazenApps</h6>
</footer>
<?php endif; ?>
