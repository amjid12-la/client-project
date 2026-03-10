 <!-- FOOTER -->
   <footer class="site-footer">
    <div class="footer-container">

        <!-- ABOUT -->
        <div class="footer-col">
            <h3>{{ $footerAbout->title ?? 'Report System' }}</h3>
            <p>
                {{ $footerAbout->content ?? 'A secure and reliable platform to submit and manage reports. Your information helps build transparency and accountability.' }}
            </p>
        </div>

        <!-- LINKS -->
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="#report-form">Submit Report</a></li>
                <li><a href="#report-table">Search Reports</a></li>
            </ul>
        </div>

        <!-- CONTACT -->
        <div class="footer-col">
            <h4>{{ $footerContact->title ?? 'Contact' }}</h4>
            <p>Email: {{ $footerContact->email ?? 'support@example.com' }}</p>
            <p>Phone: {{ $footerContact->phone ?? '123456789' }}</p>
            <p>Location: {{ $footerContact->location ?? 'XYZ' }}</p>
        </div>

    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} Report System. All rights reserved.
    </div>
</footer>

