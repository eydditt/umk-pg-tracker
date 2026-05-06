<x-filament-widgets::widget>
    <div
        x-data="{
            time: '',
            date: '',
            todayFull: '',
            msg: '',
            sub: '',
            days: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
            daysFull: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            pad(n) { return String(n).padStart(2, '0') },
            tick() {
                const now  = new Date()
                const h    = now.getHours()
                const min  = now.getMinutes()
                const s    = now.getSeconds()
                const ampm = h >= 12 ? 'PM' : 'AM'
                const h12  = h % 12 || 12
                const dow  = now.getDay()

                this.time = this.pad(h12) + ':' + this.pad(min) + ':' + this.pad(s) + ' ' + ampm
                this.date = this.days[dow] + ', ' + now.getDate() + ' ' + this.months[now.getMonth()] + ' ' + now.getFullYear()
                this.todayFull = 'Today is ' + this.daysFull[dow] + ' 📅'

                if (h < 12) {
                    this.msg = 'Good morning! ☀️'
                    this.sub = 'Start the day strong — check pending applicants first.'
                } else if (h < 17) {
                    this.msg = 'Good afternoon! 🌤️'
                    this.sub = 'Keep up the great work managing your records.'
                } else {
                    this.msg = 'Good evening! 🌙'
                    this.sub = 'Wrapping up for the day? Review today\'s updates.'
                }
            },
            init() {
                this.tick()
                setInterval(() => this.tick(), 1000)
            }
        }"
        x-init="init()"
        style="
            background: var(--fi-color-gray-800, #1f2937);
            border: 1px solid var(--fi-color-gray-700, rgba(255,255,255,0.08));
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        "
    >
        {{-- Left --}}
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <div x-text="msg" style="font-size: 20px; font-weight: 600; color: var(--fi-color-gray-50, #f9fafb);"></div>
            <div x-text="todayFull" style="font-size: 14px; font-weight: 500; color: #2A9D8F; margin-top: 2px;"></div>
            <div x-text="sub" style="font-size: 13px; color: var(--fi-color-gray-400, #9ca3af);"></div>

            {{-- Day strip --}}
            <div style="display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap;">
                <template x-for="(d, i) in days" :key="i">
                    <span
                        x-text="d"
                        :style="i === new Date().getDay()
                            ? 'background:#2A9D8F; color:#fff; border-color:#2A9D8F; font-weight:600; font-size:12px; padding:3px 10px; border-radius:999px; border:1px solid #2A9D8F;'
                            : 'font-size:12px; padding:3px 10px; border-radius:999px; border:1px solid rgba(255,255,255,0.15); color:var(--fi-color-gray-400, #9ca3af); background:var(--fi-color-gray-700, rgba(255,255,255,0.05));'"
                    ></span>
                </template>
            </div>
        </div>

        {{-- Right --}}
        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
            <div x-text="time" style="font-size: 32px; font-weight: 600; color: var(--fi-color-gray-50, #f9fafb); font-variant-numeric: tabular-nums; letter-spacing: -1px;"></div>
            <div x-text="date" style="font-size: 13px; color: var(--fi-color-gray-400, #9ca3af);"></div>
        </div>
    </div>
</x-filament-widgets::widget>