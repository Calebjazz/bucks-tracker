{{-- One shared delete confirmation. Triggered by any element with data-confirm-delete="Item name". --}}
<dialog id="confirm-dialog" aria-labelledby="confirm-dialog-title" aria-describedby="confirm-dialog-text"
    class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border border-line bg-white p-0 text-ink shadow-2xl shadow-slate-900/20">
    <div class="flex gap-4 p-6">
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
            <x-icon name="alert-triangle" class="size-5" />
        </span>
        <div>
            <h2 id="confirm-dialog-title" class="text-base font-semibold">Delete this item?</h2>
            <p id="confirm-dialog-text" class="mt-1.5 text-sm text-muted">
                <strong data-confirm-name class="font-semibold text-ink"></strong> will be permanently removed. This action cannot be undone.
                <span data-confirm-note class="mt-2 block empty:hidden"></span>
            </p>
        </div>
    </div>
    <div class="flex flex-col-reverse gap-2 border-t border-line bg-slate-50/70 px-6 py-4 sm:flex-row sm:justify-end">
        <x-button variant="secondary" data-dialog-close autofocus>Cancel</x-button>
        <x-button variant="danger" icon="trash" data-confirm-accept>Delete</x-button>
    </div>
</dialog>
