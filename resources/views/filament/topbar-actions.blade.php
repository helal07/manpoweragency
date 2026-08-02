<div style="display: flex; align-items: center; gap: 0.75rem; margin-right: 0.75rem;">
    <!-- Live Status Indicator -->
    <div style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #059669; white-space: nowrap;">
        <span style="display: inline-block; width: 0.5rem; height: 0.5rem; border-radius: 9999px; background-color: #10b981; box-shadow: 0 0 6px #10b981;"></span>
        <span>System Live</span>
    </div>

    <!-- RL License Pill -->
    <div style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.65rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #d97706; white-space: nowrap;">
        <svg style="width: 0.85rem; height: 0.85rem; color: #f59e0b; flex-shrink: 0; display: inline-block;" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
        </svg>
        <span>RL-1452</span>
    </div>

    <!-- Quick Action: Visit Live Website -->
    <a href="{{ url('/') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.85rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #ffffff; text-decoration: none; background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%); border: 1px solid rgba(59, 130, 246, 0.5); box-shadow: 0 2px 4px rgba(0,0,0,0.1); white-space: nowrap;">
        <svg style="width: 0.85rem; height: 0.85rem; color: #93c5fd; flex-shrink: 0; display: inline-block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        <span>Live Website</span>
    </a>
</div>
