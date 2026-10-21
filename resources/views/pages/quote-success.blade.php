@extends('layouts.website')

@section('meta_description', 'Thanks for your outdoor advertising quote brief. Speak to our team on WhatsApp or open the company profile while we prepare your proposal.')

@section('content')
    <section class="em-container py-16 lg:py-24">
        <div class="mx-auto max-w-3xl rounded-2xl border border-[#e6cdb8] bg-white p-8 shadow-[0_24px_60px_-40px_rgba(120,52,41,0.35)] sm:p-10">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8b1e1a]">Thank you</p>
            <h1 class="mt-3 text-[1.65rem] font-black leading-tight text-[#5c1514] sm:text-3xl">
                Your quote request is with our campaigns desk
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-[#5e5e5e]">
                We have logged your briefing details. Expect a thoughtful response tailored to counties, corridors, and the media formats that match your goals.
            </p>

            <div class="mt-8 grid gap-3 sm:flex sm:flex-wrap">
                @if ($profilePdfViewUrl)
                    <a href="{{ $profilePdfViewUrl }}" target="_blank" rel="noopener noreferrer" class="em-btn-secondary min-h-[48px] inline-flex items-center justify-center px-5 text-center font-semibold">
                        View company profile (PDF)
                    </a>
                @endif
                @if ($profilePdfDownloadUrl)
                    <a href="{{ $profilePdfDownloadUrl }}" class="em-btn-secondary min-h-[48px] inline-flex items-center justify-center border-[#128C7E]/40 bg-[#128C7E]/10 px-5 text-center font-semibold text-[#0d6e64] hover:bg-[#128C7E]/15" download>
                        Download profile
                    </a>
                @endif
                <button type="button" class="em-btn-secondary min-h-[48px] px-5 text-center font-semibold" data-open-download-modal>
                    Request profile via email
                </button>
            </div>

            <div class="mt-10 rounded-xl border border-[#eedfd3] bg-[#fffdfb] px-5 py-5">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#69332c]">Faster lane</p>
                <p class="mt-2 text-sm text-[#4b3531]">Message WhatsApp sales with additions to your brief or reference timelines — the same inbox reviews this submission.</p>
                <a
                    href="{{ $whatsappHrefQuote }}"
                    class="mt-4 flex min-h-[48px] w-full items-center justify-center rounded-xl bg-[#25D366] px-4 text-sm font-bold text-white shadow-sm transition hover:brightness-[1.05] sm:w-auto sm:min-w-[220px]"
                    target="_blank"
                    rel="noopener noreferrer"
                    data-track-event="whatsapp_clicked"
                    data-track-location="quote_success"
                >
                    WhatsApp Effective Media
                </a>
            </div>

            <div class="mt-10 flex flex-wrap gap-3 border-t border-[#ece2d9] pt-8">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-[#8b1e1a] underline-offset-4 hover:underline">Back to home</a>
                <span class="hidden text-[#cbb6a9] sm:inline" aria-hidden="true">·</span>
                <a href="{{ route('quote') }}" class="text-sm font-semibold text-[#57453f] underline-offset-4 hover:underline">Submit another brief</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if (! empty($quoteJustSubmitted))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.emTrackEvent) {
                    window.emTrackEvent('quote_submitted', { page: 'quote_success', funnel: 'website_quote_form' });
                }
            });
        </script>
    @endif
@endpush
