<?php
declare(strict_types=1);
require_once __DIR__ . '/../../bootstrap.php';
rbac_guard('dashboard.view');
require_once INCLUDES_PATH . '/header.php';
?>
<style>.clock-analog{width:120px;height:120px;border:3px solid #d9d9d9;border-radius:50%;position:relative;margin:0 auto;background:#fafafa}.clock-hand{position:absolute;left:50%;top:50%;transform-origin:bottom;background:#000;border-radius:2px}.hour-hand{width:4px;height:32px;margin-top:-32px}.minute-hand{width:3px;height:45px;margin-top:-45px}.second-hand{width:2px;height:50px;background:red;margin-top:-50px}.clock-center{width:8px;height:8px;background:#000;border-radius:50%;position:absolute;left:50%;top:50%;transform:translate(-50%,-50%)}#weather-widget{color:#1e1e2d;border-radius:12px;transition:all .3s ease;padding:20px !important}#weather-widget .temp{font-size:42px;font-weight:700;margin-bottom:4px}#weather-widget .icon{font-size:42px}#weather-widget .bottom-forecast{margin-top:20px}#plant-widget .plant-emoji{font-size:58px;line-height:1;margin-bottom:10px}#plant-widget{padding-top:20px;padding-bottom:20px}#plant-widget button{border-radius:8px}</style>
<div class="row g-5 gx-xl-10 mb-5 mb-xl-10 mt-5">
    <div id="kt_content_container" class="container-xxl">

        <div class="row g-5">

            <!-- CLOCK WIDGET -->
            <div class="col-md-3 col-xl-3">
                <div class="card h-100 shadow-sm" style="border-radius:22px;">
                    <div class="card-body text-center" id="clock-widget">
                        <div class="clock-analog">
                            <div class="clock-hand hour-hand" :style="hourStyle"></div>
                            <div class="clock-hand minute-hand" :style="minuteStyle"></div>
                            <div class="clock-hand second-hand" :style="secondStyle"></div>
                            <div class="clock-center"></div>
                        </div>
                        <div class="fs-1 fw-bold mb-1">{{ time }}</div>
                        <div class="fs-6 text-muted mb-3">{{ date }}</div>
                    </div>
                </div>
            </div>

            <!-- WEATHER WIDGET -->
            <div class="col-md-3 col-xl-3">
                <div class="card h-100 shadow-sm" style="border-radius:22px;">
                    <div id="weather-widget" class="card-body" :style="{ background: widgetStyle }">
                
                        <template v-if="loaded">
                
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="temp">{{ temp }}°C</div>
                                    <div class="text-muted small">{{ location }}</div>
                                    <div class="text-muted small">{{ description }}</div>
                                </div>
                                <div class="icon">{{ icon }}</div>
                            </div>
                
                            <hr class="my-3">
                
                            <div class="d-flex justify-content-between bottom-forecast">
                                <div v-for="d in next7" class="text-center small px-1">
                                    <div class="fw-bold">{{ d.short }}</div>
                                    <div class="fs-4">{{ d.icon }}</div>
                                    <div>{{ d.temp }}°</div>
                                </div>
                            </div>
                
                        </template>
                
                        <template v-else>
                            <div class="text-center py-10">Loading weather...</div>
                        </template>
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

$clockJs = '/admin/modules/dashboard/js/clock.js';
$weatherJs = '/admin/modules/dashboard/js/weather.js';

$pageScripts = [
    $clockJs . '?v=' . filemtime($_SERVER['DOCUMENT_ROOT'] . $clockJs),
    $weatherJs . '?v=' . filemtime($_SERVER['DOCUMENT_PATH'] ?? $_SERVER['DOCUMENT_ROOT'] . $weatherJs),
];

require_once INCLUDES_PATH . '/footer.php';
?>