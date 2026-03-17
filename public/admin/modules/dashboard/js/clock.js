const clockWidget = Vue.createApp({
    data() {
        return {
            time: "",
            date: "",
            hourStyle: {},
            minuteStyle: {},
            secondStyle: {},
        };
    },

    mounted() {
        this.updateClock();
        setInterval(this.updateClock, 1000);
    },

    methods: {
        updateClock() {
            const now = new Date();

            // DIGITAL
            this.time = now.toLocaleTimeString("en-GB", {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            });

            const d = String(now.getDate()).padStart(2, "0");
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const mmm = monthNames[now.getMonth()];
            const y = now.getFullYear();
            
            this.date = `${d} ${mmm} ${y}`;

            const secDeg = now.getSeconds() * 6;
            const minDeg = now.getMinutes() * 6;
            const hourDeg = (now.getHours() % 12) * 30 + (minDeg / 12);

            this.secondStyle = { transform: `rotate(${secDeg}deg)` };
            this.minuteStyle = { transform: `rotate(${minDeg}deg)` };
            this.hourStyle = { transform: `rotate(${hourDeg}deg)` };
        }
    }
}).mount("#clock-widget");
