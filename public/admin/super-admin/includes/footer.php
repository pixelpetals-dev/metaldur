        </div>
	</div>
</div>
						
						<div id="kt_app_footer" class="app-footer">
							<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
								<div class="text-gray-900 order-2 order-md-1">
									<span class="text-muted fw-semibold me-1">&copy;<?php echo date('Y');?></span>
									<a href="https://metaldur.in" target="_blank" class="text-gray-800 text-hover-primary">Metaldur</a>
								</div>
								<div class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
									<li>Powered by
										<a href="https://simplyfivestudio.com" target="_blank" style="color: var(--bs-primary);">Simply Five Studio</a>
									</li>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-solid ki-arrow-up"></i>
		</div>

		<script src="<?= ASSETS_URL ?>/plugins/global/plugins.bundle.js"></script>
		<script src="<?= ASSETS_URL ?>/js/scripts.bundle.js"></script>
		<script src="<?= ASSETS_URL ?>/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/vue@3.5.24/dist/vue.global.prod.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/axios@1.13.2/dist/axios.min.js"></script>
		<link  href="<?= ASSETS_URL ?>/css/quill.snow.css" rel="stylesheet"/>
        <script src="<?= ASSETS_URL ?>/js/quill.js"></script>
        <script src="<?= ASSETS_URL ?>/js/widgets.bundle.js"></script>
		<script src="<?= ASSETS_URL ?>/js/custom/widgets.js"></script>
		<script src="<?= ASSETS_URL ?>/plugins/custom/datatables/datatables.bundle.js"></script>
		<script src="<?= ASSETS_URL ?>/js/custom/apps/chat/chat.js"></script>
		<script src="<?= ASSETS_URL ?>/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="<?= ASSETS_URL ?>/js/custom/utilities/modals/create-app.js"></script>
		<script src="<?= ASSETS_URL ?>/js/custom/utilities/modals/new-target.js"></script>
		<script src="<?= ASSETS_URL ?>/js/custom/utilities/modals/users-search.js"></script>
		<script src="<?= ASSETS_URL ?>/js/select2.min.js"></script>
		<link  href="<?= ASSETS_URL ?>/css/select2.min.css" rel="stylesheet"/>
		<script src="<?= ASSETS_URL ?>/js/bootstrap-datepicker.min.js" defer></script>
		<link  href="<?= ASSETS_URL ?>/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
		
		<?php if (!empty($pageScripts ?? [])): ?>
          <?php foreach ($pageScripts as $src): ?>
            <script src="<?= htmlspecialchars($src) ?>"></script>
          <?php endforeach; ?>
        <?php endif; ?>
        
        <script src="<?= ASSETS_URL ?>/js/bootstrap.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', () => {
          document
            .querySelectorAll('[data-bs-toggle="dropdown"]')
            .forEach(el => new bootstrap.Dropdown(el));
        });
        </script>
	</body>
</html>