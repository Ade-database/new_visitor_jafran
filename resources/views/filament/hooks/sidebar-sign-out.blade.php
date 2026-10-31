<div style="padding: 0.75rem 1rem; border-top: 1px solid rgba(128, 128, 128, 0.25);">
    <button type="button"
            onclick="document.getElementById('signout-modal').style.display = 'flex'"
            style="display: flex; align-items: center; gap: 0.75rem; width: 100%; padding: 0.5rem 0.75rem; border: none; border-radius: 0.5rem; background: transparent; color: #dc2626; font-size: 0.875rem; font-weight: 500; cursor: pointer;"
            onmouseover="this.style.background='rgba(220, 38, 38, 0.08)'"
            onmouseout="this.style.background='transparent'">
        <x-filament::icon icon="heroicon-o-arrow-right-start-on-rectangle"
                          style="width: 1.5rem; height: 1.5rem;" />
        <span>Sign Out</span>
    </button>
</div>