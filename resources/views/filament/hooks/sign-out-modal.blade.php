<div id="signout-modal"
     onclick="if (event.target === this) this.style.display = 'none'"
     style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.5); padding: 1rem;">

    <div style="width: 100%; max-width: 24rem; background: #ffffff; border-radius: 1rem; padding: 1.75rem 1.5rem; text-align: center; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);">

        <div style="width: 3.5rem; height: 3.5rem; margin: 0 auto 1rem; border-radius: 9999px; background: #fee2e2; display: flex; align-items: center; justify-content: center;">
            <x-filament::icon icon="heroicon-o-arrow-right-start-on-rectangle"
                              style="width: 1.75rem; height: 1.75rem; color: #dc2626;" />
        </div>

        <h3 style="margin: 0 0 0.5rem; font-size: 1.125rem; font-weight: 700; color: #111827;">
            Keluar dari akun?
        </h3>

        <p style="margin: 0 0 1.5rem; font-size: 0.875rem; color: #6b7280;">
            Anda harus login kembali untuk mengakses dashboard.
        </p>

        <div style="display: flex; gap: 0.75rem;">
            <button type="button"
                    onclick="document.getElementById('signout-modal').style.display = 'none'"
                    style="flex: 1; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background: #ffffff; color: #374151; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                Batal
            </button>

            <form method="POST" action="{{ filament()->getLogoutUrl() }}" style="flex: 1; margin: 0;">
                @csrf
                <button type="submit"
                        style="width: 100%; padding: 0.625rem 1rem; border: none; border-radius: 0.5rem; background: #dc2626; color: #ffffff; font-size: 0.875rem; font-weight: 600; cursor: pointer;">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>