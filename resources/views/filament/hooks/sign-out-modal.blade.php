<div
    id="jafran-signout-modal"
    style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center;"
>
    {{-- Overlay --}}
    <div
        onclick="document.getElementById('jafran-signout-modal').style.display='none'"
        style="position: absolute; inset: 0; background: rgba(0,0,0,0.5);"
    ></div>

    {{-- Box modal --}}
    <div style="position: relative; background: white; border-radius: 0.75rem; padding: 1.5rem; width: 100%; max-width: 360px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="font-size: 1rem; font-weight: 600; color: #111827; margin: 0 0 0.5rem 0;">
            Konfirmasi Sign out
        </h3>
        <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 1.25rem 0;">
            Apakah Anda yakin ingin keluar dari sistem?
        </p>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
            <button
                type="button"
                onclick="document.getElementById('jafran-signout-modal').style.display='none'"
                style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background: white; color: #374151; font-size: 0.875rem; font-weight: 500; cursor: pointer;"
            >
                Batal
            </button>

            <form
                action="{{ filament()->getLogoutUrl() }}"
                method="post"
                style="margin: 0;"
                data-signout-modal-form
            >
                @csrf
                <button
                    type="submit"
                    style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; background: #dc2626; color: white; font-size: 0.875rem; font-weight: 500; cursor: pointer;"
                >
                    Ya, Sign out
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('submit', function (e) {
        const form = e.target;

        // Lewati form modal kita sendiri
        if (!form || form.hasAttribute('data-signout-modal-form')) {
            return;
        }

        const logoutUrl = @json(filament()->getLogoutUrl());

        // Kalau form ini menuju URL logout bawaan Filament (misal dari dropdown profil kanan atas)
        if (form.action && form.action.replace(/\/$/, '') === logoutUrl.replace(/\/$/, '')) {
            e.preventDefault();
            document.getElementById('jafran-signout-modal').style.display = 'flex';
        }
    });
</script>