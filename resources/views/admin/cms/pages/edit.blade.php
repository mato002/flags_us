@extends('layouts.admin')

@section('title', 'Website Page CMS | Effective Media')

@section('header')
    Edit · {{ str_replace('-', ' ', $slug) }}
@endsection

@section('content')
    @include('admin.partials.page-header', [
        'title' => 'Page Builder · '.str_replace('-', ' ', $slug),
        'description' => 'Visual page builder for marketing and content teams.',
        'breadcrumb' => 'Website CMS · Page Builder',
    ])

    <form method="POST" action="{{ route('admin.cms.pages.update', ['slug' => $slug]) }}" class="space-y-5 rounded-lg border border-slate-200 bg-white p-6 text-sm dark:border-white/10 dark:bg-white/[0.04]" data-cms-page-builder-form>
        @csrf
        @method('PUT')
        <input type="hidden" name="sections_json" value="">
        <input type="hidden" name="is_published" value="{{ old('is_published', $page->is_published ? '1' : '0') }}" data-cms-published-hidden>

        <section class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 dark:border-white/10 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 dark:text-[#cbbfb6]">Page details</p>
                    <h2 class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ ucwords(str_replace('-', ' ', $slug)) }}</h2>
                    <p class="mt-1 text-xs text-slate-600 dark:text-[#bfb4ad]">
                        Slug: <span class="font-mono">{{ $slug }}</span> · Last updated: {{ optional($page->updated_at)?->format('M j, Y H:i') ?? '—' }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" class="rounded-md border border-slate-300 px-3 py-2 text-xs font-semibold dark:border-white/20 dark:text-white" data-cms-published-toggle>
                        {{ $page->is_published ? 'Published' : 'Draft' }}
                    </button>
                    <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="rounded-md border border-slate-300 px-3 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">Preview</a>
                    <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="rounded-md border border-slate-300 px-3 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">View Live</a>
                    <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-xs font-semibold text-white hover:bg-[#f04a2a]">Save Changes</button>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 p-4 dark:border-white/10">
            <h3 class="text-sm font-bold uppercase tracking-wide text-slate-700 dark:text-[#d8c9bf]">SEO</h3>
            <p class="mt-1 text-xs text-slate-500 dark:text-[#bfb4ad]">Controls title tags, social previews, indexing, and the canonical URL for this page.</p>
            <div class="mt-3 grid gap-4 lg:grid-cols-2">
                <label class="block">
                    <span class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">SEO title</span>
                    <input id="title" name="title" value="{{ old('title', $page->title) }}" required maxlength="70" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
                    <span class="mt-1 block text-[11px] text-slate-500"><span data-seo-title-count>0</span>/60 recommended</span>
                </label>
                <label class="block">
                    <span class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Open Graph image URL</span>
                    <div class="mt-1 flex gap-2">
                        <input id="og_image" name="og_image" value="{{ old('og_image', $page->og_image) }}" class="w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white" placeholder="https://… or /storage/…">
                        <label class="inline-flex cursor-pointer items-center rounded border border-slate-300 px-3 py-2 text-xs font-semibold dark:border-white/20 dark:text-white">
                            Upload
                            <input type="file" class="hidden" accept="image/*" data-cms-seo-upload>
                        </label>
                    </div>
                </label>
                <label class="block lg:col-span-2">
                    <span class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Meta description</span>
                    <textarea id="meta_description" name="meta_description" rows="3" maxlength="500" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">{{ old('meta_description', $page->meta_description) }}</textarea>
                    <span class="mt-1 block text-[11px] text-slate-500"><span data-seo-desc-count>0</span>/155–160 recommended (max 500)</span>
                </label>
                <label class="block">
                    <span class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Robots</span>
                    @php($robotsVal = old('robots', $page->robots ?? ''))
                    <select id="robots" name="robots" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white">
                        <option value="" @selected($robotsVal === '')>Default (index, follow)</option>
                        <option value="index, follow" @selected($robotsVal === 'index, follow')>index, follow</option>
                        <option value="noindex, follow" @selected($robotsVal === 'noindex, follow')>noindex, follow</option>
                        <option value="index, nofollow" @selected($robotsVal === 'index, nofollow')>index, nofollow</option>
                        <option value="noindex, nofollow" @selected($robotsVal === 'noindex, nofollow')>noindex, nofollow</option>
                    </select>
                </label>
                <label class="block">
                    <span class="text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Canonical URL override</span>
                    <input id="canonical_url" name="canonical_url" value="{{ old('canonical_url', $page->canonical_url) }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 dark:border-white/20 dark:bg-transparent dark:text-white" placeholder="Leave blank to use the live page URL">
                </label>
            </div>
            <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs dark:border-white/10 dark:bg-white/[0.03]">
                <p class="font-semibold text-slate-700 dark:text-[#d8c9bf]">SEO preview</p>
                <p class="mt-1 text-[13px] font-semibold text-[#1a0dab]" data-seo-preview-title>{{ old('title', $page->title) }}</p>
                <p class="text-[11px] text-[#006621]" data-seo-preview-url>{{ $previewUrl }}</p>
                <p class="mt-1 text-[12px] text-slate-600 dark:text-[#bfb4ad]" data-seo-preview-description>{{ old('meta_description', $page->meta_description) }}</p>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 p-4 dark:border-white/10">
            <div class="mb-3 flex items-center justify-between gap-2">
                <h3 class="text-sm font-bold uppercase tracking-wide text-slate-700 dark:text-[#d8c9bf]">Section Builder</h3>
                <button type="button" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-semibold dark:border-white/20 dark:text-white" data-cms-add-section>Add Section</button>
            </div>
            <div class="space-y-3" data-cms-sections-root></div>
        </section>

        @error('sections_json')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror

        <details class="rounded-md border border-slate-200 bg-slate-50/60 p-3 text-xs dark:border-white/10 dark:bg-white/[0.03]">
            <summary class="cursor-pointer font-semibold text-slate-700 dark:text-[#d8c9bf]">Developer Mode (raw JSON + legacy body)</summary>
            <div class="mt-3 space-y-3">
                <div>
                    <label for="body" class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Legacy HTML body</label>
                    <textarea id="body" name="body" rows="6" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 font-mono text-xs dark:border-white/20 dark:bg-transparent dark:text-white">{{ old('body', $page->body) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-[#cbbfb6]">Raw sections JSON (manual override)</label>
                    <textarea rows="10" class="mt-1 w-full rounded border border-slate-300 px-3 py-2 font-mono text-xs dark:border-white/20 dark:bg-transparent dark:text-white" data-cms-raw-json></textarea>
                </div>
            </div>
        </details>

        <div class="flex flex-wrap gap-2">
            <button type="submit" class="rounded-md bg-[#8b1e1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#f04a2a]">Save Changes</button>
            <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold dark:border-white/20 dark:text-white">View Live Page</a>
            <a href="{{ route('admin.cms.pages.index') }}" class="rounded-md px-4 py-2 text-sm font-semibold text-slate-600 underline-offset-2 hover:underline dark:text-[#cbbfb6]">All pages</a>
        </div>
    </form>

    <script type="application/json" id="cms-builder-initial">
        {!! json_encode(json_decode(old('sections_json', $sectionsPretty !== '' ? $sectionsPretty : $fullSectionsPretty), true) ?: [], JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const initialNode = document.getElementById('cms-builder-initial');
            const root = document.querySelector('[data-cms-sections-root]');
            const form = document.querySelector('[data-cms-page-builder-form]');
            const hiddenJson = form?.querySelector('input[name="sections_json"]');
            const rawJson = document.querySelector('[data-cms-raw-json]');
            const addSectionBtn = document.querySelector('[data-cms-add-section]');
            const seoTitle = document.getElementById('title');
            const seoDescription = document.getElementById('meta_description');
            const previewTitle = document.querySelector('[data-seo-preview-title]');
            const previewDesc = document.querySelector('[data-seo-preview-description]');
            const titleCount = document.querySelector('[data-seo-title-count]');
            const descCount = document.querySelector('[data-seo-desc-count]');
            const ogImageInput = document.getElementById('og_image');
            const seoUpload = document.querySelector('[data-cms-seo-upload]');
            const publishBtn = document.querySelector('[data-cms-published-toggle]');
            const publishHidden = document.querySelector('[data-cms-published-hidden]');

            const syncSeoCounters = () => {
                if (titleCount) titleCount.textContent = String((seoTitle?.value || '').length);
                if (descCount) descCount.textContent = String((seoDescription?.value || '').length);
            };
            seoTitle?.addEventListener('input', () => {
                if (previewTitle) previewTitle.textContent = seoTitle.value || 'Untitled page';
                syncSeoCounters();
            });
            seoDescription?.addEventListener('input', () => {
                if (previewDesc) previewDesc.textContent = seoDescription.value || '';
                syncSeoCounters();
            });
            syncSeoCounters();

            if (!(root instanceof HTMLElement) || !(form instanceof HTMLFormElement) || !(hiddenJson instanceof HTMLInputElement)) return;

            const uploadEndpoint = @json(route('admin.cms.pages.upload-media'));
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            let sectionsState = [];
            let sectionCounter = 1;

            const initialRaw = (() => {
                try {
                    return JSON.parse(initialNode?.textContent || '{}');
                } catch {
                    return {};
                }
            })();

            const mapObjToState = (obj) => Object.entries(obj).map(([key, value]) => ({
                id: `s_${sectionCounter++}`,
                key,
                enabled: true,
                data: value,
                collapsed: false,
            }));

            const clone = (value) => JSON.parse(JSON.stringify(value));
            const keyLabel = (k) => String(k).replace(/[_-]+/g, ' ').replace(/\b\w/g, (m) => m.toUpperCase());
            const fieldType = (key, value) => {
                const k = String(key).toLowerCase();
                if (typeof value === 'boolean') return 'boolean';
                if (typeof value === 'number') return 'number';
                if (k.endsWith('_url') || k.includes('link')) return 'url';
                if (k.includes('image') || k.includes('logo') || k.includes('icon') || k.includes('pdf') || k.includes('file') || k.includes('document')) return 'media';
                if (typeof value === 'string' && (value.length > 120 || k.includes('description') || k.includes('body') || k.includes('text'))) return 'textarea';
                return 'text';
            };

            const getAtPath = (obj, path) => path.reduce((acc, p) => acc?.[p], obj);
            const setAtPath = (obj, path, value) => {
                const parent = path.slice(0, -1).reduce((acc, p) => acc[p], obj);
                parent[path[path.length - 1]] = value;
            };
            const removeAtPath = (obj, path) => {
                const parent = path.slice(0, -1).reduce((acc, p) => acc[p], obj);
                const last = path[path.length - 1];
                if (Array.isArray(parent)) parent.splice(Number(last), 1);
                else delete parent[last];
            };

            const toObjectForSave = () => {
                const out = {};
                sectionsState.forEach((s) => {
                    if (!s.enabled) return;
                    out[s.key] = s.data;
                });
                return out;
            };

            const syncPayload = () => {
                const payload = toObjectForSave();
                const json = JSON.stringify(payload, null, 2);
                hiddenJson.value = json;
                if (rawJson instanceof HTMLTextAreaElement) rawJson.value = json;
            };

            const uploadAsset = async (file) => {
                const fd = new FormData();
                fd.append('asset', file);
                const res = await fetch(uploadEndpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        Accept: 'application/json',
                    },
                    body: fd,
                });
                if (!res.ok) throw new Error('Upload failed');
                return res.json();
            };

            const makeField = (section, path, key, value) => {
                const wrap = document.createElement('div');
                wrap.className = 'rounded-md border border-slate-200 bg-white p-3 dark:border-white/10 dark:bg-white/[0.02]';

                const label = document.createElement('label');
                label.className = 'mb-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-[#bfb4ad]';
                label.textContent = keyLabel(key);
                wrap.appendChild(label);

                const type = fieldType(key, value);
                const onValue = (newValue) => {
                    setAtPath(section.data, path, newValue);
                    syncPayload();
                };

                if (type === 'boolean') {
                    const input = document.createElement('input');
                    input.type = 'checkbox';
                    input.checked = Boolean(value);
                    input.className = 'h-4 w-4 rounded border-slate-300';
                    input.addEventListener('change', () => onValue(input.checked));
                    wrap.appendChild(input);
                    return wrap;
                }

                if (type === 'textarea') {
                    const ta = document.createElement('textarea');
                    ta.rows = 3;
                    ta.value = value ?? '';
                    ta.className = 'w-full rounded border border-slate-300 px-3 py-2 text-sm dark:border-white/20 dark:bg-transparent dark:text-white';
                    ta.addEventListener('input', () => onValue(ta.value));
                    wrap.appendChild(ta);
                    return wrap;
                }

                const row = document.createElement('div');
                row.className = 'flex gap-2';
                const input = document.createElement('input');
                input.type = type === 'number' ? 'number' : type === 'url' ? 'url' : 'text';
                input.value = value ?? '';
                input.className = 'w-full rounded border border-slate-300 px-3 py-2 text-sm dark:border-white/20 dark:bg-transparent dark:text-white';
                input.addEventListener('input', () => onValue(type === 'number' ? Number(input.value || 0) : input.value));
                row.appendChild(input);

                if (type === 'media') {
                    const upLabel = document.createElement('label');
                    upLabel.className = 'inline-flex cursor-pointer items-center rounded border border-slate-300 px-3 py-2 text-xs font-semibold dark:border-white/20 dark:text-white';
                    upLabel.textContent = 'Upload';
                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.className = 'hidden';
                    fileInput.accept = 'image/*,.pdf';
                    fileInput.addEventListener('change', async () => {
                        const file = fileInput.files?.[0];
                        if (!file) return;
                        upLabel.textContent = 'Uploading...';
                        try {
                            const result = await uploadAsset(file);
                            input.value = result.url || result.path || '';
                            onValue(input.value);
                        } catch {
                            alert('Upload failed. Please try again.');
                        } finally {
                            upLabel.textContent = 'Upload';
                            fileInput.value = '';
                        }
                    });
                    upLabel.appendChild(fileInput);
                    row.appendChild(upLabel);
                }

                wrap.appendChild(row);
                return wrap;
            };

            const renderNode = (section, current, path, mount) => {
                if (Array.isArray(current)) {
                    const container = document.createElement('div');
                    container.className = 'space-y-2';

                    current.forEach((item, index) => {
                        const itemPath = [...path, index];
                        const card = document.createElement('div');
                        card.className = 'rounded-md border border-slate-200 bg-slate-50 p-2 dark:border-white/10 dark:bg-white/[0.03]';
                        const head = document.createElement('div');
                        head.className = 'mb-2 flex items-center justify-between';
                        head.innerHTML = `<span class="text-xs font-semibold text-slate-600 dark:text-[#bfb4ad]">Item ${index + 1}</span>`;
                        const actions = document.createElement('div');
                        actions.className = 'flex gap-1';
                        const dup = document.createElement('button');
                        dup.type = 'button';
                        dup.className = 'rounded border border-slate-300 px-2 py-1 text-[11px] dark:border-white/20';
                        dup.textContent = 'Duplicate';
                        dup.addEventListener('click', () => {
                            const arr = getAtPath(section.data, path);
                            arr.splice(index + 1, 0, clone(item));
                            rerender();
                        });
                        const del = document.createElement('button');
                        del.type = 'button';
                        del.className = 'rounded border border-rose-300 px-2 py-1 text-[11px] text-rose-600';
                        del.textContent = 'Delete';
                        del.addEventListener('click', () => {
                            removeAtPath(section.data, itemPath);
                            rerender();
                        });
                        actions.appendChild(dup);
                        actions.appendChild(del);
                        head.appendChild(actions);
                        card.appendChild(head);

                        if (typeof item === 'object' && item !== null) {
                            const grid = document.createElement('div');
                            grid.className = 'grid gap-2 md:grid-cols-2';
                            Object.entries(item).forEach(([k, v]) => renderNode(section, v, [...itemPath, k], grid));
                            card.appendChild(grid);
                        } else {
                            card.appendChild(makeField(section, itemPath, String(path[path.length - 1]), item));
                        }
                        container.appendChild(card);
                    });

                    const addBtn = document.createElement('button');
                    addBtn.type = 'button';
                    addBtn.className = 'rounded border border-slate-300 px-3 py-1.5 text-xs font-semibold dark:border-white/20 dark:text-white';
                    addBtn.textContent = 'Add item';
                    addBtn.addEventListener('click', () => {
                        const arr = getAtPath(section.data, path);
                        const sample = arr[0];
                        arr.push(sample && typeof sample === 'object' ? clone(sample) : '');
                        rerender();
                    });
                    container.appendChild(addBtn);
                    mount.appendChild(container);
                    return;
                }

                if (typeof current === 'object' && current !== null) {
                    const card = document.createElement('div');
                    card.className = 'rounded-lg border border-slate-200 p-3 dark:border-white/10';
                    if (path.length) {
                        const title = document.createElement('p');
                        title.className = 'mb-2 text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-[#bfb4ad]';
                        title.textContent = keyLabel(path[path.length - 1]);
                        card.appendChild(title);
                    }
                    const grid = document.createElement('div');
                    grid.className = 'grid gap-2 md:grid-cols-2';
                    Object.entries(current).forEach(([k, v]) => renderNode(section, v, [...path, k], grid));
                    card.appendChild(grid);
                    mount.appendChild(card);
                    return;
                }

                const key = String(path[path.length - 1] ?? 'value');
                mount.appendChild(makeField(section, path, key, current));
            };

            const rerender = () => {
                root.innerHTML = '';
                sectionsState.forEach((section, index) => {
                    const card = document.createElement('details');
                    card.className = 'rounded-xl border border-slate-200 bg-slate-50/50 p-3 dark:border-white/10 dark:bg-white/[0.02]';
                    if (!section.collapsed) card.setAttribute('open', '');
                    card.addEventListener('toggle', () => {
                        section.collapsed = !card.open;
                    });

                    const summary = document.createElement('summary');
                    summary.className = 'cursor-pointer list-none';
                    summary.innerHTML = `
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">${keyLabel(section.key)}</p>
                                <p class="text-[11px] text-slate-500 dark:text-[#bfb4ad]">${section.key}</p>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <button type="button" class="rounded border border-slate-300 px-2 py-1 text-[11px] dark:border-white/20" data-act="up">↑</button>
                                <button type="button" class="rounded border border-slate-300 px-2 py-1 text-[11px] dark:border-white/20" data-act="down">↓</button>
                                <button type="button" class="rounded border border-slate-300 px-2 py-1 text-[11px] dark:border-white/20" data-act="duplicate">Duplicate</button>
                                <button type="button" class="rounded border border-rose-300 px-2 py-1 text-[11px] text-rose-600" data-act="remove">Remove</button>
                                <label class="inline-flex items-center gap-1 rounded border border-slate-300 px-2 py-1 text-[11px] dark:border-white/20">
                                    <input type="checkbox" ${section.enabled ? 'checked' : ''} data-act="toggle">
                                    Enabled
                                </label>
                            </div>
                        </div>
                    `;
                    summary.querySelector('[data-act="up"]')?.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (index === 0) return;
                        [sectionsState[index - 1], sectionsState[index]] = [sectionsState[index], sectionsState[index - 1]];
                        rerender();
                    });
                    summary.querySelector('[data-act="down"]')?.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (index === sectionsState.length - 1) return;
                        [sectionsState[index + 1], sectionsState[index]] = [sectionsState[index], sectionsState[index + 1]];
                        rerender();
                    });
                    summary.querySelector('[data-act="duplicate"]')?.addEventListener('click', (e) => {
                        e.preventDefault();
                        sectionsState.splice(index + 1, 0, {
                            ...clone(section),
                            id: `s_${sectionCounter++}`,
                            key: `${section.key}_copy_${Date.now().toString().slice(-4)}`,
                        });
                        rerender();
                    });
                    summary.querySelector('[data-act="remove"]')?.addEventListener('click', (e) => {
                        e.preventDefault();
                        sectionsState.splice(index, 1);
                        rerender();
                    });
                    summary.querySelector('[data-act="toggle"]')?.addEventListener('change', (e) => {
                        section.enabled = e.target.checked;
                        syncPayload();
                    });
                    card.appendChild(summary);

                    const body = document.createElement('div');
                    body.className = 'mt-3 space-y-2';
                    renderNode(section, section.data, [], body);
                    card.appendChild(body);
                    root.appendChild(card);
                });
                syncPayload();
            };

            if (typeof initialRaw === 'object' && initialRaw !== null) {
                sectionsState = mapObjToState(initialRaw);
            }

            addSectionBtn?.addEventListener('click', () => {
                const key = `new_section_${sectionsState.length + 1}`;
                sectionsState.push({
                    id: `s_${sectionCounter++}`,
                    key,
                    enabled: true,
                    data: { title: '', description: '' },
                    collapsed: false,
                });
                rerender();
            });

            seoUpload?.addEventListener('change', async () => {
                const file = seoUpload.files?.[0];
                if (!file || !(ogImageInput instanceof HTMLInputElement)) return;
                try {
                    const result = await uploadAsset(file);
                    ogImageInput.value = result.url || result.path || '';
                } catch {
                    alert('SEO image upload failed.');
                } finally {
                    seoUpload.value = '';
                }
            });

            publishBtn?.addEventListener('click', () => {
                if (!(publishHidden instanceof HTMLInputElement)) return;
                const next = publishHidden.value !== '1';
                publishHidden.value = next ? '1' : '0';
                publishBtn.textContent = next ? 'Published' : 'Draft';
                publishBtn.classList.toggle('bg-amber-50', !next);
            });

            rawJson?.addEventListener('change', () => {
                try {
                    const parsed = JSON.parse(rawJson.value || '{}');
                    if (typeof parsed === 'object' && parsed !== null) {
                        sectionsState = mapObjToState(parsed);
                        rerender();
                    }
                } catch {
                    alert('Raw JSON is invalid.');
                }
            });

            form.addEventListener('submit', () => {
                syncPayload();
            });

            rerender();
        });
    </script>
@endsection
