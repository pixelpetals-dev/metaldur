const weatherWidget = Vue.createApp({
    data() {
        return {
            loaded: false,
            temp: "",
            description: "",
            location: "Detecting...",
            icon: "",
            next7: [],
            widgetStyle: "",
        };
    },

    mounted() {
        this.loadWeather();
    },

    methods: {
        async loadWeather() {
            try {
                // --- USER LOCATION ---
                const locReq = await fetch("https://ipapi.co/json/");
                const loc = await locReq.json();

                this.location = loc.city || "Your location";
                const lat = loc.latitude;
                const lon = loc.longitude;

                // --- WEATHER API ---
                const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true&daily=weathercode,temperature_2m_max&timezone=auto`;
                const res = await fetch(url);
                const data = await res.json();

                // Current
                this.temp = Math.round(data.current_weather.temperature);
                const code = data.current_weather.weathercode;
                this.description = this.mapCode(code);
                this.icon = this.mapIcon(code);

                // 7-Day Forecast
                const days = data.daily;
                this.next7 = days.weathercode.map((c, i) => ({
                    date: days.time[i],
                    short: this.getDayShort(days.time[i]),
                    icon: this.mapIcon(c),
                    temp: Math.round(days.temperature_2m_max[i]),
                }));

                // BG animation
                this.widgetStyle = this.mapBackground(code);

                this.loaded = true;
            } catch (e) {
                console.error("Weather load error:", e);
            }
        },

        // WEATHER TEXT
        mapCode(code) {
            const desc = {
                0: "Clear",
                1: "Mainly clear",
                2: "Partly cloudy",
                3: "Overcast",
                45: "Fog",
                48: "Rime fog",
                51: "Drizzle",
                61: "Light rain",
                63: "Rain",
                65: "Heavy rain",
                71: "Light snow",
                80: "Rain showers",
            };
            return desc[code] || "Weather";
        },

        // ANIMATED ICONS
        mapIcon(code) {
            if (code === 0) return "☀️";
            if (code === 1 || code === 2) return "🌤️";
            if (code === 3) return "☁️";
            if (code >= 51 && code <= 65) return "🌧️";
            if (code >= 71) return "❄️";
            return "🌡️";
        },

        // BACKGROUND ANIMATION (iOS-like gradients)
        mapBackground(code) {
            const styles = {
                clear: "linear-gradient(135deg, #fed28b, #ff9f43)",
                cloudy: "linear-gradient(135deg, #d7dbe8, #b5c0d0)",
                rain: "linear-gradient(135deg, #9ec4ff, #6793e8)",
                snow: "linear-gradient(135deg, #e6f3ff, #bcd8ff)",
            };

            if (code === 0) return styles.clear;
            if (code === 1 || code === 2 || code === 3) return styles.cloudy;
            if (code >= 51 && code <= 65) return styles.rain;
            if (code >= 71) return styles.snow;

            return styles.clear;
        },

        // DAY LABEL (Mon, Tue…)
        getDayShort(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleString("en", { weekday: "short" });
        }
    }
}).mount("#weather-widget");
