<style>
    .fcc-modern-footer-container {
        padding: 0;
        margin-top: auto !important;
        width: 100%;
    }

    .fcc-modern-footer {
        background: #ffffff;
        border: none;
        border-top: 1px solid #e2e8f0;
        border-radius: 0;
        padding: 10px 20px;
        width: 100%;
        max-width: 100%;
        margin: 0;
        font-family: 'Outfit', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #64748b;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        box-shadow: none;
    }

    .fcc-footer-left {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .fcc-footer-brand-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 12.5px;
        letter-spacing: -0.01em;
    }

    .fcc-footer-dot-sep {
        color: #cbd5e1;
    }

    .fcc-footer-copy-text {
        color: #64748b;
        font-weight: 500;
    }

    .fcc-footer-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .fcc-footer-made-with {
        color: #64748b;
        font-size: 12px;
        font-weight: 500;
    }

    .fcc-footer-heart-anim {
        color: #ef4444 !important;
        font-family: 'FontAwesome' !important;
        display: inline-block;
        animation: fccPulseHeart 1.8s ease infinite;
        margin: 0 1px;
        font-size: 11px;
    }

    @keyframes fccPulseHeart {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .fcc-footer-brand-link {
        color: #3b46f1;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .fcc-footer-brand-link:hover {
        color: #1d28cd;
        text-decoration: underline;
    }
</style>

<div class="fcc-modern-footer-container">
    <footer class="fcc-modern-footer">
        <div class="fcc-footer-left">
            <span class="fcc-footer-brand-title">Fit Coach Club</span>
            <span class="fcc-footer-dot-sep">·</span>
            <span class="fcc-footer-copy-text">Copyright &copy; {{ date("Y") }} All rights reserved.</span>
        </div>

        <div class="fcc-footer-right">
            <span class="fcc-footer-made-with">
                Made in India with <i class="fa fa-heart fcc-footer-heart-anim"></i> by 
                <a href="https://digiyog.com" target="_blank" class="fcc-footer-brand-link">Digiyog Technosoft</a>
            </span>
        </div>
    </footer>
</div>