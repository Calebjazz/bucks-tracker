/**
 * ExpenseFlow UI behaviour — plain JavaScript, no framework.
 *
 * Everything is wired through data-* attributes in the Blade views:
 *
 *   data-dialog-open="id"      opens the <dialog id="id"> (optional data-dialog-fill='{"title":…,"fields":{…}}')
 *   data-dialog-close          closes the surrounding dialog
 *   data-confirm-delete="Name" asks for confirmation, then removes the closest [data-deletable]
 *   data-dropdown              wraps a [data-dropdown-trigger] button and a [data-dropdown-menu]
 *   data-sidebar-open / -close toggles the mobile sidebar
 *   data-toggle="id"           shows / hides the element with that id
 *   data-password-toggle       shows / hides the password next to it
 *   data-demo-form             validates a form in the browser and fakes a successful submit
 *   data-export="#table"       downloads that table as a CSV file
 *   data-tooltip="text"        shows a tooltip on hover / focus (used by the charts)
 *
 * When the Laravel backend exists, remove data-demo-form from a form so it submits to the server.
 */

const SPINNER =
    '<svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>';
const CHECK =
    '<svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>';

/* -------------------------------------------------------------------------- */
/* Toasts                                                                     */
/* -------------------------------------------------------------------------- */

export function showToast(message, variant = 'success') {
    const region = document.getElementById('toast-region');
    const template = document.getElementById('toast-template');

    if (!region || !template) {
        return;
    }

    const toast = template.content.firstElementChild.cloneNode(true);
    toast.querySelector('[data-toast-message]').textContent = message;
    toast.querySelector(`[data-toast-icon]:not([data-toast-icon="${variant}"])`)?.remove();

    const dismiss = () => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 200);
    };

    toast.querySelector('[data-toast-close]').addEventListener('click', dismiss);
    region.append(toast);
    requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.remove('opacity-0', 'translate-y-2')));
    setTimeout(dismiss, 4500);
}

window.showToast = showToast;

/* -------------------------------------------------------------------------- */
/* Buttons                                                                    */
/* -------------------------------------------------------------------------- */

function setLoading(button, loading, text = 'Saving…') {
    if (!button) {
        return;
    }

    if (loading) {
        button.dataset.originalHtml = button.innerHTML;
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        button.innerHTML = `${SPINNER}<span>${button.dataset.loadingText ?? text}</span>`;

        return;
    }

    button.disabled = false;
    button.removeAttribute('aria-busy');
    button.innerHTML = button.dataset.originalHtml ?? button.innerHTML;
}

/* -------------------------------------------------------------------------- */
/* Form validation (client-side preview of Laravel's validation errors)       */
/* -------------------------------------------------------------------------- */

function fieldLabel(field) {
    return field.dataset.label ?? field.labels?.[0]?.textContent.trim() ?? 'This field';
}

function errorElement(field) {
    const key = field.type === 'radio' ? field.name : field.id || field.name;

    return document.getElementById(`${key}-error`);
}

function validationMessage(field) {
    const validity = field.validity;

    if (field.dataset.match && field.value) {
        const other = field.form.elements.namedItem(field.dataset.match);

        if (other && other.value !== field.value) {
            return field.dataset.matchMessage ?? 'The passwords do not match.';
        }
    }

    if (validity.valid) {
        return '';
    }

    if (validity.valueMissing) {
        return field.dataset.requiredMessage ?? `${fieldLabel(field)} is required.`;
    }

    if (validity.typeMismatch && field.type === 'email') {
        return 'Enter a valid email address, like name@example.com.';
    }

    if (validity.tooShort) {
        return `Use at least ${field.minLength} characters.`;
    }

    if (validity.rangeUnderflow) {
        return `Enter an amount of at least ${Number(field.min).toLocaleString()}.`;
    }

    if (validity.badInput) {
        return 'Enter a valid number.';
    }

    return field.validationMessage;
}

function setError(field, message) {
    const fields = field.type === 'radio' ? field.form.querySelectorAll(`[name="${field.name}"]`) : [field];

    fields.forEach((input) =>
        message ? input.setAttribute('aria-invalid', 'true') : input.removeAttribute('aria-invalid'),
    );

    const element = errorElement(field);

    if (element) {
        element.textContent = message;
        element.hidden = !message;
    }
}

function clearErrors(form) {
    form.querySelectorAll('[aria-invalid]').forEach((field) => field.removeAttribute('aria-invalid'));
    form.querySelectorAll('[data-error]').forEach((element) => {
        element.textContent = '';
        element.hidden = true;
    });
}

function afterSubmit(form) {
    const { redirect, toast, reveal, hide } = form.dataset;

    if (redirect) {
        window.location.href = redirect;

        return;
    }

    form.closest('dialog')?.close();

    if (reveal) {
        document.querySelectorAll('[data-echo]').forEach((element) => {
            element.textContent = form.elements.namedItem(element.dataset.echo)?.value ?? '';
        });
        document.querySelector(hide)?.setAttribute('hidden', '');
        document.querySelector(reveal)?.removeAttribute('hidden');
    }

    if ('resetOnSuccess' in form.dataset) {
        form.reset();
    }

    if (toast) {
        showToast(toast);
    }
}

document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-demo-form]');

    if (!form) {
        return;
    }

    event.preventDefault();

    let firstInvalid = null;

    for (const field of form.elements) {
        if (!field.willValidate) {
            continue;
        }

        const message = validationMessage(field);
        setError(field, message);
        firstInvalid ??= message ? field : null;
    }

    if (firstInvalid) {
        firstInvalid.focus();

        return;
    }

    const button = event.submitter ?? form.querySelector('[type="submit"]');
    setLoading(button, true);

    setTimeout(() => {
        setLoading(button, false);
        afterSubmit(form);
    }, 900);
});

['input', 'change'].forEach((type) =>
    document.addEventListener(type, (event) => {
        const field = event.target;

        if (field.form?.matches('[data-demo-form]') && field.getAttribute('aria-invalid') === 'true') {
            setError(field, validationMessage(field));
        }
    }),
);

/* Show only the categories that match the selected transaction type. */
function syncCategoryOptions(form) {
    const select = form?.querySelector('[data-category-select]');
    const type = form?.elements.namedItem('type')?.value;

    if (!select || !type) {
        return;
    }

    select.querySelectorAll('optgroup').forEach((group) => {
        const matches = group.dataset.type === type;
        group.hidden = !matches;
        group.disabled = !matches;
    });

    if (select.selectedOptions[0]?.parentElement?.disabled) {
        select.value = '';
    }
}

document.addEventListener('change', (event) => {
    if (event.target.name === 'type') {
        syncCategoryOptions(event.target.form);
    }
});

document.querySelectorAll('form').forEach(syncCategoryOptions);

/* -------------------------------------------------------------------------- */
/* Dialogs (native <dialog>: focus trapping and Esc come for free)            */
/* -------------------------------------------------------------------------- */

function fillForm(form, fields) {
    for (const [name, value] of Object.entries(fields)) {
        const field = form.elements.namedItem(name);

        if (!field) {
            continue;
        }

        if (field.type === 'checkbox') {
            field.checked = Boolean(value);
        } else {
            field.value = value ?? '';
        }
    }
}

function openDialog(id, trigger) {
    const dialog = document.getElementById(id);

    if (!dialog) {
        return;
    }

    const form = dialog.querySelector('form');
    const fill = trigger?.dataset.dialogFill ? JSON.parse(trigger.dataset.dialogFill) : {};
    const title = dialog.querySelector('[data-dialog-title]');

    if (form) {
        form.reset();
        clearErrors(form);
        fillForm(form, fill.fields ?? {});
        syncCategoryOptions(form);
    }

    if (title) {
        title.dataset.defaultTitle ??= title.textContent;
        title.textContent = fill.title ?? title.dataset.defaultTitle;
    }

    dialog.showModal();
}

let pendingDelete = null;

document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-dialog-open]');

    if (opener) {
        openDialog(opener.dataset.dialogOpen, opener);
    }

    if (event.target.closest('[data-dialog-close]')) {
        event.target.closest('dialog')?.close();
    }

    /* Clicking the dark backdrop closes the dialog. */
    if (event.target instanceof HTMLDialogElement) {
        event.target.close();
    }

    const deleteTrigger = event.target.closest('[data-confirm-delete]');

    if (deleteTrigger) {
        const dialog = document.getElementById('confirm-dialog');
        pendingDelete = { element: deleteTrigger.closest('[data-deletable]'), name: deleteTrigger.dataset.confirmDelete };
        dialog.querySelector('[data-confirm-name]').textContent = pendingDelete.name;
        dialog.querySelector('[data-confirm-note]').textContent = deleteTrigger.dataset.confirmNote ?? '';
        dialog.showModal();
    }

    const accept = event.target.closest('[data-confirm-accept]');

    if (accept && pendingDelete) {
        setLoading(accept, true, 'Deleting…');

        setTimeout(() => {
            setLoading(accept, false);
            accept.closest('dialog').close();

            const { element, name } = pendingDelete;
            pendingDelete = null;

            if (element) {
                element.style.transition = 'opacity 200ms ease';
                element.style.opacity = '0';
                setTimeout(() => element.remove(), 200);
            }

            showToast(`“${name}” was deleted.`);
        }, 700);
    }
});

/* -------------------------------------------------------------------------- */
/* Dropdown menus                                                             */
/* -------------------------------------------------------------------------- */

function closeDropdowns({ except = null, restoreFocus = false } = {}) {
    document.querySelectorAll('[data-dropdown-menu]:not([hidden])').forEach((menu) => {
        if (menu === except) {
            return;
        }

        const trigger = menu.closest('[data-dropdown]').querySelector('[data-dropdown-trigger]');
        menu.hidden = true;
        trigger.setAttribute('aria-expanded', 'false');

        if (restoreFocus) {
            trigger.focus();
        }
    });
}

/* Menus use position: fixed so they are never clipped by scrollable tables. */
function positionMenu(trigger, menu) {
    const rect = trigger.getBoundingClientRect();
    const { offsetWidth: width, offsetHeight: height } = menu;
    const preferred = menu.dataset.align === 'left' ? rect.left : rect.right - width;
    const left = Math.min(Math.max(8, preferred), window.innerWidth - width - 8);
    const fitsBelow = rect.bottom + 6 + height <= window.innerHeight - 8;
    const top = fitsBelow || rect.top - height - 6 < 8 ? rect.bottom + 6 : rect.top - height - 6;

    menu.style.left = `${left}px`;
    menu.style.top = `${top}px`;
}

function menuItems(menu) {
    return [...menu.querySelectorAll('[role="menuitem"]:not(:disabled)')];
}

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-dropdown-trigger]');

    if (trigger) {
        const menu = trigger.closest('[data-dropdown]').querySelector('[data-dropdown-menu]');
        const willOpen = menu.hidden;
        closeDropdowns();

        if (willOpen) {
            menu.hidden = false;
            trigger.setAttribute('aria-expanded', 'true');
            positionMenu(trigger, menu);

            if (event.detail === 0) {
                menuItems(menu)[0]?.focus();
            }
        }

        return;
    }

    if (!event.target.closest('[data-dropdown-menu]') || event.target.closest('[role="menuitem"]')) {
        closeDropdowns();
    }
});

document.addEventListener('keydown', (event) => {
    const menu = event.target.closest?.('[data-dropdown-menu]');

    if (event.key === 'Escape' && document.querySelector('[data-dropdown-menu]:not([hidden])')) {
        closeDropdowns({ restoreFocus: true });

        return;
    }

    if (menu && ['ArrowDown', 'ArrowUp'].includes(event.key)) {
        event.preventDefault();
        const items = menuItems(menu);
        const index = items.indexOf(document.activeElement);
        const next = event.key === 'ArrowDown' ? index + 1 : index - 1;
        items.at(next % items.length)?.focus();
    }
});

window.addEventListener('resize', () => closeDropdowns());
window.addEventListener(
    'scroll',
    (event) => {
        if (!event.target.closest?.('[data-dropdown-menu]')) {
            closeDropdowns();
        }
    },
    true,
);

/* -------------------------------------------------------------------------- */
/* Sidebar, toggles and password visibility                                   */
/* -------------------------------------------------------------------------- */

const sidebar = document.querySelector('[data-sidebar]');
const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

function setSidebar(open) {
    if (!sidebar) {
        return;
    }

    sidebar.toggleAttribute('data-open', open);
    sidebarOverlay.hidden = !open;
    document.querySelector('[data-sidebar-open]')?.setAttribute('aria-expanded', String(open));

    if (open) {
        sidebar.querySelector('a, button')?.focus();
    }
}

document.addEventListener('click', (event) => {
    if (event.target.closest('[data-sidebar-open]')) {
        setSidebar(true);
    }

    if (event.target.closest('[data-sidebar-close], [data-sidebar-overlay]')) {
        setSidebar(false);
    }

    const toggle = event.target.closest('[data-toggle]');

    if (toggle) {
        const target = document.getElementById(toggle.dataset.toggle);
        target.hidden = !target.hidden;
        toggle.setAttribute('aria-expanded', String(!target.hidden));
    }

    const passwordToggle = event.target.closest('[data-password-toggle]');

    if (passwordToggle) {
        const input = passwordToggle.parentElement.querySelector('input');
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        passwordToggle.setAttribute('aria-pressed', String(visible));
        passwordToggle.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
        passwordToggle.querySelectorAll('svg').forEach((icon) => icon.classList.toggle('hidden'));
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && sidebar?.hasAttribute('data-open')) {
        setSidebar(false);
    }
});

/* -------------------------------------------------------------------------- */
/* Report export                                                              */
/* -------------------------------------------------------------------------- */

function tableToCsv(table) {
    return [...table.querySelectorAll('tr')]
        .map((row) =>
            [...row.querySelectorAll('th, td')]
                .map((cell) => `"${cell.textContent.trim().replace(/\s+/g, ' ').replace(/"/g, '""')}"`)
                .join(','),
        )
        .join('\n');
}

document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-export]');

    if (!button || button.disabled) {
        return;
    }

    setLoading(button, true, 'Preparing report…');

    setTimeout(() => {
        const table = document.querySelector(button.dataset.export);
        const link = document.createElement('a');
        link.href = URL.createObjectURL(new Blob([tableToCsv(table)], { type: 'text/csv' }));
        link.download = button.dataset.filename ?? 'report.csv';
        link.click();
        URL.revokeObjectURL(link.href);

        button.innerHTML = `${CHECK}<span>Report downloaded</span>`;
        showToast('Your report was exported as a CSV file.');

        setTimeout(() => setLoading(button, false), 2000);
    }, 1200);
});

/* -------------------------------------------------------------------------- */
/* Chart tooltips                                                             */
/* -------------------------------------------------------------------------- */

const tooltip = document.createElement('div');
tooltip.setAttribute('role', 'tooltip');
tooltip.className =
    'pointer-events-none fixed z-50 hidden max-w-64 rounded-lg bg-ink px-3 py-2 text-xs leading-5 whitespace-pre-line text-white shadow-lg';
document.body.append(tooltip);

function showTooltip(element, x, y) {
    tooltip.textContent = element.dataset.tooltip;
    tooltip.classList.remove('hidden');

    const left = Math.min(x + 14, window.innerWidth - tooltip.offsetWidth - 8);
    const top = Math.max(8, y - tooltip.offsetHeight - 12);
    tooltip.style.left = `${left}px`;
    tooltip.style.top = `${top}px`;
}

document.addEventListener('pointermove', (event) => {
    const element = event.target.closest?.('[data-tooltip]');

    if (element) {
        showTooltip(element, event.clientX, event.clientY);
    } else {
        tooltip.classList.add('hidden');
    }
});

document.addEventListener('focusin', (event) => {
    const element = event.target.closest?.('[data-tooltip]');

    if (element) {
        const rect = element.getBoundingClientRect();
        showTooltip(element, rect.left + rect.width / 2, rect.top);
    }
});

document.addEventListener('focusout', () => tooltip.classList.add('hidden'));

/* -------------------------------------------------------------------------- */
/* Flash messages from Laravel: session()->flash('status', '…')               */
/* -------------------------------------------------------------------------- */

document.querySelectorAll('[data-flash-toast]').forEach((element) => showToast(element.dataset.flashToast));
