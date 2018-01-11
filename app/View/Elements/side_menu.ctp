<div class="actions <?php echo $debugMode;?> sideMenu">
	<ul class="nav nav-list">
		<?php
			switch ($menuList) {
				case 'event':
		?>
					<div id="hiddenSideMenuData" class="hidden" data-event-id="<?php echo isset($event['Event']['id']) ? h($event['Event']['id']) : 0; ?>"></div>
		<?php
					if (
						$menuItem === 'addAttribute' ||
						$menuItem === 'addObject' ||
						$menuItem === 'addAttachment' ||
						$menuItem === 'addIOC' ||
						$menuItem === 'addThreatConnect' ||
						$menuItem === 'populateFromtemplate'
					) {
						// we can safely assume that mayModify is true if coming from these actions, as they require it in the controller and the user has already passed that check
						$mayModify = true;
						if ($isAclPublish) $mayPublish = true;
					}
					if (($menuItem === 'template_populate_results')):
					?>
						<li id='litemplate_populate_results'><a href="<?php echo $baseurl;?>/templates/index"><?php echo __('Populate From Template');?></a></li>
					<?php
						endif;
					?>
					<?php if ($menuItem === 'freetextResults'): ?>
					<li id='lifreetextResults'><a href="#"><?php echo __('Freetext Import Results');?></a></li>
					<li class="divider"></li>
					<?php endif;?>
					<li id='liviewEvent'><a href="<?php echo $baseurl;?>/events/view/<?php echo h($event['Event']['id']);?>"><?php echo __('View Event');?></a></li>
					<li id='liviewGraph'><a href="<?php echo $baseurl;?>/events/viewGraph/<?php echo h($event['Event']['id']);?>"><?php echo __('View Correlation Graph');?></a></li>
					<li id='lieventLog'><a href="<?php echo $baseurl;?>/logs/event_index/<?php echo h($event['Event']['id']);?>"><?php echo __('View Event History');?></a></li>
					<li class="divider"></li>
					<?php if ($isSiteAdmin || (isset($mayModify) && $mayModify)): ?>
					<li id='lieditEvent'><a href="<?php echo $baseurl;?>/events/edit/<?php echo h($event['Event']['id']);?>"><?php echo __('Edit Event');?></a></li>
					<li><?php echo $this->Form->postLink(__('Delete Event'), array('controller' => 'events', 'action' => 'delete', h($event['Event']['id'])), null, __('Are you sure you want to delete # %s?', h($event['Event']['id']))); ?></li>
					<li id='liaddAttribute'><a href="<?php echo $baseurl;?>/attributes/add/<?php echo h($event['Event']['id']);?>"><?php echo __('Add Attribute');?></a></li>
					<li><a onClick="getPopup('<?php echo h($event['Event']['id']); ?>', 'objectTemplates', 'objectChoice');" style="cursor:pointer;"><?php echo __('Add Object');?></a></li>
					<li id='liaddAttachment'><a href="<?php echo $baseurl;?>/attributes/add_attachment/<?php echo h($event['Event']['id']);?>"><?php echo __('Add Attachment');?></a></li>
					<li id='import'><a onClick="getPopup('<?php echo h($event['Event']['id']); ?>', 'events', 'importChoice');" style="cursor:pointer;"><?php echo __('Populate from…');?></a></li>
					<?php if ($menuItem === 'populateFromtemplate'): ?>
							<li class="active"><a href="<?php echo $baseurl;?>/templates/populateEventFromTemplate/<?php echo $template_id . '/' . h($event['Event']['id']); ?>"><?php echo __('Populate From Template');?></a></li>
						<?php endif; ?>
					<li id='merge'><a href="<?php echo $baseurl;?>/events/merge/<?php echo h($event['Event']['id']);?>"><?php echo __('Merge attributes from…');?></a></li>
					<?php endif; ?>
					<?php if (($isSiteAdmin && (!isset($mayModify) || !$mayModify)) || (!isset($mayModify) || !$mayModify)): ?>
					<li id='liproposeAttribute'><a href="<?php echo $baseurl;?>/shadow_attributes/add/<?php echo h($event['Event']['id']);?>"><?php echo __('Propose Attribute');?></a></li>
					<li id='liproposeAttachment'><a href="<?php echo $baseurl;?>/shadow_attributes/add_attachment/<?php echo h($event['Event']['id']);?>"><?php echo __('Propose Attachment');?></a></li>
					<?php endif; ?>
					<li class="divider"></li>
					<?php
						$publishButtons = ' hidden';
						if (isset($event['Event']['published']) && 0 == $event['Event']['published'] && ($isSiteAdmin || (isset($mayPublish) && $mayPublish))) $publishButtons = "";
					?>
					<li class="publishButtons not-published<?php echo h($publishButtons); ?>"><a href="#" onClick="publishPopup('<?php echo h($event['Event']['id']); ?>', 'alert')"><?php echo __('Publish Event');?></a></li>
					<li class="publishButtons not-published<?php echo h($publishButtons); ?>"><a href="#" onClick="publishPopup('<?php echo h($event['Event']['id']); ?>', 'publish')"><?php echo __('Publish (no email)');?></a></li>
					<?php if (Configure::read('MISP.delegation')):?>
						<?php if ((Configure::read('MISP.unpublishedprivate') || (isset($event['Event']['distribution']) && $event['Event']['distribution'] == 0)) && (!isset($delegationRequest) || !$delegationRequest) && ($isSiteAdmin || (isset($isAclDelegate) && $isAclDelegate))): ?>
								<li id='lidelegateEvent'><a href="#" onClick="delegatePopup('<?php echo h($event['Event']['id']); ?>');"><?php echo __('Delegate Publishing');?></a></li>
						<?php endif;?>
						<?php if (isset($delegationRequest) && $delegationRequest && ($isSiteAdmin || ($isAclPublish && ($me['org_id'] == $delegationRequest['EventDelegation']['org_id'] || $me['org_id'] == $delegationRequest['EventDelegation']['requester_org_id'])))): ?>
							<li class="divider"></li>
							<?php if ($isSiteAdmin || ($isAclPublish && ($me['org_id'] == $delegationRequest['EventDelegation']['org_id']))): ?>
								<li id='liacceptDelegation'><a href="#" onClick="genericPopup('<?php echo $baseurl?>/event_delegations/acceptDelegation/<?php echo h($delegationRequest['EventDelegation']['id']); ?>', '#confirmation_box');"><?php echo __('Accept Delegation Request');?></a></li>
							<?php endif;?>
							<li id='lideleteDelegation'><a href="#" onClick="genericPopup('<?php echo $baseurl?>/event_delegations/deleteDelegation/<?php echo h($delegationRequest['EventDelegation']['id']); ?>', '#confirmation_box');"><?php echo __('Discard Delegation Request');?></a></li>
							<li class="divider"></li>
						<?php endif;?>
					<?php endif;?>
					<?php if (Configure::read('Plugin.ZeroMQ_enable') && $isSiteAdmin): ?>
						<li><?php echo $this->Form->postLink(__('Publish event to ZMQ'), array('action' => 'pushEventToZMQ', $event['Event']['id']));?></li>
					<?php endif; ?>
					<li id='licontact'><a href="<?php echo $baseurl;?>/events/contact/<?php echo h($event['Event']['id']);?>"><?php echo __('Contact Reporter');?></a></li>
					<li><a onClick="getPopup('<?php echo h($event['Event']['id']); ?>', 'events', 'exportChoice');" style="cursor:pointer;"><?php echo __('Download as…');?></a></li>
					<li class="divider"></li>
					<li><a href="<?php echo $baseurl;?>/events/index"><?php echo __('List Events');?></a></li>
					<?php if ($isAclAdd): ?>
					<li><a href="<?php echo $baseurl;?>/events/add"><?php echo __('Add Event');?></a></li>
					<?php endif;
				break;

				case 'event-collection': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/events/index"><?php echo __('List Events');?></a></li>
					<?php if ($isAclAdd): ?>
					<li id='liadd'><a href="<?php echo $baseurl;?>/events/add"><?php echo __('Add Event');?></a></li>
					<li id='liaddMISPExport'><a href="<?php echo $baseurl;?>/events/add_misp_export"><?php echo __('Import From MISP Export');?></a></li>
					<?php endif; ?>
					<li class="divider"></li>
					<li id='lilistAttributes'><a href="<?php echo $baseurl;?>/attributes/index"><?php echo __('List Attributes');?></a></li>
					<li id='lisearchAttributes'><a href="<?php echo $baseurl;?>/attributes/search"><?php echo __('Search Attributes');?></a></li>
					<?php if ($menuItem == 'searchAttributes2'): ?>
					<li class="divider"></li>
					<li><a href="<?php echo $baseurl;?>/events/downloadSearchResult.json"><?php echo __('Download results as JSON');?></a></li>
					<li><a href="<?php echo $baseurl;?>/events/downloadSearchResult.xml"><?php echo __('Download results as XML');?></a></li>
					<li><a href="<?php echo $baseurl;?>/events/csv/download/search"><?php echo __('Download results as CSV');?></a></li>
					<?php endif; ?>
					<li class="divider"></li>
					<li id='liviewProposals'><a href="<?php echo $baseurl;?>/shadow_attributes/index"><?php echo __('View Proposals');?></a></li>
					<li id='liviewProposalIndex'><a href="<?php echo $baseurl;?>/events/proposalEventIndex"><?php echo __('Events with proposals');?></a></li>
					<li class="divider"></li>
					<li id='liexport'><a href="<?php echo $baseurl;?>/events/export"><?php echo __('Export');?></a></li>
					<?php if ($isAclAuth): ?>
					<li id='liautomation'><a href="<?php echo $baseurl;?>/events/automation"><?php echo __('Automation');?></a></li>
					<?php endif;
				break;

				case 'regexp': ?>
					<li id='liindex'><?php echo $this->Html->link('List Regexp', array('admin' => $isSiteAdmin, 'action' => 'index'));?></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liadd'><?php echo $this->Html->link('New Regexp', array('admin' => true, 'action' => 'add'));?></li>
					<li><?php echo $this->Form->postLink(__('Perform on existing'), array('admin' => true, 'action' => 'clean'));?></li>
					<?php endif;
					if ($menuItem == 'edit'):?>
					<li class="divider"></li>
					<li class="active"><?php echo $this->Html->link('Edit Regexp', array('admin' => true, 'action' => 'edit', h($id)));?></li>
					<li><?php echo $this->Form->postLink(__('Delete Regexp'), array('admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s?', h($id)));?></li>
					<?php
					endif;
				break;
				case 'warninglist':?>
					<?php if ($menuItem == 'view'): ?><li class="active"><a href="#"><?php echo __('View Warninglist');?></a></li><?php endif;?>
					<li id='liindex'><?php echo $this->Html->link(__('List Warninglists'), array('action' => 'index'));?></li>
					<?php if ($isSiteAdmin): ?>
					<li><?php echo $this->Form->postLink(__('Update Warninglists'), '/warninglists/update'); ?></li>
					<?php
						endif;
				break;
				case 'whitelist':?>
					<li id='liindex'><?php echo $this->Html->link(__('List Whitelist'), array('admin' => $isSiteAdmin, 'action' => 'index'));?></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liadd'><?php echo $this->Html->link(__('New Whitelist'), array('admin' => true, 'action' => 'add'));?></li>
					<?php endif;
					if ($menuItem == 'edit'):?>
					<li class="divider"></li>
					<li class="active"><?php echo $this->Html->link(__('Edit Whitelist'), array('admin' => true, 'action' => 'edit', h($id)));?></li>
					<li><?php echo $this->Form->postLink(__('Delete Whitelist'), array('admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s?', h($id)));?></li>
					<?php
					endif;
				break;

				case 'globalActions':
					if (((Configure::read('MISP.disableUserSelfManagement') && $isAdmin) || !Configure::read('MISP.disableUserSelfManagement')) && ($menuItem === 'edit' || $menuItem === 'view')): ?>
					<li id='liedit'><?php echo $this->Html->link(__('Edit My Profile', true), array('action' => 'edit')); ?></li>
					<li id='liedit'><?php echo $this->Html->link(__('Change Password', true), array('action' => 'change_pw')); ?></li>
					<li class="divider"></li>
					<?php elseif (Configure::read('Plugin.CustomAuth_custom_password_reset')): ?>
					<li id='lipwreset'><a href="<?php echo h(Configure::read('Plugin.CustomAuth_custom_password_reset'));?>">Reset Password</a></li>
					<?php endif; ?>
					<li id='liview'><a href="<?php echo $baseurl;?>/users/view/me"><?php echo __('My Profile');?></a></li>
					<li id='lidashboard'><a href="<?php echo $baseurl;?>/users/dashboard"><?php echo __('Dashboard');?></a></li>
					<li id='liindexOrg'><a href="<?php echo $baseurl;?>/organisations/index"><?php echo __('List Organisations');?></a></li>
					<?php if ($menuItem === 'viewOrg'): ?>
						<li class="active"><a href="<?php echo $baseurl;?>/organisations/view/<?php echo h($id);?>"><?php echo __('View Organisation');?></a></li>
					<?php endif;?>
					<li id='liroles'><a href="<?php echo $baseurl;?>/roles/index"><?php echo __('Role Permissions');?></a></li>
					<li class="divider"></li>
					<?php if ($menuItem === 'editSG' || ($menuItem == 'viewSG' && $mayModify)): ?>
						<li id='lieditSG'><a href="<?php echo $baseurl;?>/sharing_groups/edit/<?php echo h($id); ?>"><?php echo __('Edit Sharing Group');?></a></li>
						<li id='liviewSG'><a href="<?php echo $baseurl;?>/sharing_groups/view/<?php echo h($id);?>"><?php echo __('View Sharing Group');?></a></li>
					<?php endif; ?>
					<li id='liindexSG'><a href="<?php echo $baseurl;?>/sharing_groups/index"><?php echo __('List Sharing Groups');?></a></li>
					<li id='liaddSG'><a href="<?php echo $baseurl;?>/sharing_groups/add"><?php echo __('Add Sharing Group');?></a></li>
					<li class="divider"></li>
					<li id='liuserGuide'><a href="<?php echo $baseurl;?>/pages/display/doc/general"><?php echo __('User Guide');?></a></li>
					<li id='literms'><a href="<?php echo $baseurl;?>/users/terms"><?php echo __('Terms &amp; Conditions');?></a></li>
					<li id='listatistics'><a href="<?php echo $baseurl;?>/users/statistics"><?php echo __('Statistics');?></a></li>
					<?php
				break;

				case 'sync':
					if ($menuItem === 'previewEvent' && $isSiteAdmin) : ?>
					<li class="active"><?php echo $this->Html->link(__('Explore Remote Event'), array('controller' => 'servers', 'action' => 'previewEvent', h($server['Server']['id']), h($event['Event']['id']))); ?></li>
					<li><?php echo $this->Form->postLink(__('Fetch This Event'), '/servers/pull/' . $server['Server']['id'] . '/' . $event['Event']['id'], null, __('Are you sure you want to fetch and save this event on your instance?', $this->Form->value('Server.id'))); ?></li>
					<li><?php echo $this->Html->link(__('Explore Remote Server'), array('controller' => 'servers', 'action' => 'previewIndex', h($server['Server']['id']))); ?></li>
					<?php endif;
					if ($menuItem === 'previewIndex' && $isSiteAdmin) : ?>
					<li class="active"><?php echo $this->Html->link(__('Explore Remote Server'), array('controller' => 'servers', 'action' => 'previewIndex', h($id))); ?></li>
					<?php endif; ?>
					<?php if ($menuItem === 'edit' && $isSiteAdmin): ?>
					<li class="active"><?php echo $this->Html->link(__('Edit Server'), array('controller' => 'servers', 'action' => 'edit')); ?></li>
					<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Server.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Server.id'))); ?></li>
					<li class="divider"></li>
					<?php endif; ?>
					<li id='liindex'><?php echo $this->Html->link(__('List Servers'), array('controller' => 'servers', 'action' => 'index'));?></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liadd'><?php echo $this->Html->link(__('New Server'), array('controller' => 'servers', 'action' => 'add')); ?></li>
					<?php endif;?>
					<?php
				break;

				case 'admin':
					if ($menuItem === 'editUser' || $menuItem === 'viewUser'): ?>
					<li id='liviewUser'><?php echo $this->Html->link(__('View User'), array('controller' => 'users', 'action' => 'view', 'admin' => true, h($id))); ?> </li>
					<li><a href="#/" onClick="initiatePasswordReset('<?php echo h($id); ?>');"><?php echo __('Reset Password');?></a></li>
					<li id='lieditUser'><?php echo $this->Html->link(__('Edit User'), array('controller' => 'users', 'action' => 'edit', 'admin' => true, h($id))); ?> </li>
					<li><?php echo $this->Form->postLink(__('Delete User'), array('admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s? It is highly recommended to never delete users but to disable them instead.', h($id)));?></li>
					<li class="divider"></li>
					<?php endif;
					if ($isSiteAdmin && $menuItem === 'editRole'): ?>
					<li class="active"><?php echo $this->Html->link(__('Edit Role'), array('controller' => 'roles', 'action' => 'edit', 'admin' => true, h($id))); ?> </li>
					<li><?php echo $this->Form->postLink(__('Delete Role'), array('controller' => 'roles', 'admin' => true, 'action' => 'delete', h($id)), null, __('Are you sure you want to delete # %s?', h($id)));?></li>
					<li class="divider"></li>
					<?php endif;
					if ($isSiteAdmin): ?>
					<li id='liaddUser'><?php echo $this->Html->link(__('Add User'), array('controller' => 'users', 'action' => 'add', 'admin' => true)); ?> </li>
					<li id='liindexUser'><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index', 'admin' => true)); ?> </li>
					<?php endif; ?>
					<?php if ($isAdmin): ?>
					<li id='licontact'><?php echo $this->Html->link(__('Contact Users'), array('controller' => 'users', 'action' => 'email', 'admin' => true)); ?> </li>
					<?php endif; ?>
					<li class="divider"></li>
					<?php if ($isSiteAdmin): ?>
					<li id='liaddOrg'><a href="<?php echo $baseurl;?>/admin/organisations/add"><?php echo __('Add Organisation');?></a></li>
					<?php if ($menuItem === 'editOrg' || $menuItem === 'viewOrg'): ?>
						<li id='lieditOrg'><a href="<?php echo $baseurl;?>/admin/organisations/edit/<?php echo h($id);?>"><?php echo __('Edit Organisation');?></a></li>
						<li id='limergeOrg'><a class="useCursorPointer" onClick="getPopup('<?php echo h($id); ?>', 'organisations', 'merge', 'admin');"><?php echo __('Merge Organisation');?></a></li>
					<?php endif;?>
					<?php if ($menuItem === 'editOrg' || $menuItem === 'viewOrg'): ?>
						<li id='liviewOrg'><a href="<?php echo $baseurl;?>/organisations/view/<?php echo h($id);?>"><?php echo __('View Organisation');?></a></li>
					<?php endif;?>
					<li id='liindexOrg'><a href="<?php echo $baseurl;?>/organisations/index"><?php echo __('List Organisations');?></a></li>
					<li class="divider"></li>
					<li id='liaddRole'><?php echo $this->Html->link('Add Role', array('controller' => 'roles', 'action' => 'add', 'admin' => true)); ?> </li>
					<?php endif; ?>
					<li id='liindexRole'><?php echo $this->Html->link('List Roles', array('controller' => 'roles', 'action' => 'index', 'admin' => true)); ?> </li>
					<?php if ($isSiteAdmin): ?>
						<li class="divider"></li>
						<li id='liserverSettings'><a href="<?php echo $baseurl;?>/servers/serverSettings"><?php echo __('Server Settings');?></a></li>
						<li class="divider"></li>
						<?php if (Configure::read('MISP.background_jobs')): ?>
							<li id='lijobs'><a href="<?php echo $baseurl;?>/jobs/index"><?php echo __('Jobs');?></a></li>
							<li class="divider"></li>
							<li id='litasks'><a href="<?php echo $baseurl;?>/tasks"><?php echo __('Scheduled Tasks');?></a></li>
						<?php endif;
						if (Configure::read('MISP.enableEventBlacklisting') !== false): ?>
							<li <?php if ($menuItem === 'eventBlacklistsAdd') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/eventBlacklists/add"><?php echo __('Blacklists Event');?></a></li>
							<li <?php if ($menuItem === 'eventBlacklists') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/eventBlacklists"><?php echo __('Manage Event Blacklists');?></a></li>
						<?php endif;
						if (!Configure::check('MISP.enableOrgBlacklisting') || Configure::read('MISP.enableOrgBlacklisting') !== false): ?>
							<li <?php if ($menuItem === 'orgBlacklistsAdd') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/orgBlacklists/add"><?php echo __('Blacklists Organisation');?></a></li>
							<li <?php if ($menuItem === 'orgBlacklists') echo 'class="active"';?>><a href="<?php echo $baseurl;?>/orgBlacklists"><?php echo __('Manage Org Blacklists');?></a></li>
						<?php endif;
					endif;
				break;

				case 'logs': ?>
					<li id='liindex'><?php echo $this->Html->link(__('List Logs'), array('admin' => true, 'action' => 'index'));?></li>
					<li id='lisearch'><?php echo $this->Html->link(__('Search Logs'), array('admin' => true, 'action' => 'search'));?></li>
					<?php
				break;

				case 'threads':

					if ($menuItem === 'add' || $menuItem === 'view') {
						if (!(empty($thread_id) && empty($target_type))) { ?>
					<li  id='view'><?php echo $this->Html->link(__('View Thread'), array('controller' => 'threads', 'action' => 'view', h($thread_id)));?></li>
					<li  id='add'><?php echo $this->Html->link(__('Add Post'), array('controller' => 'posts', 'action' => 'add', 'thread', h($thread_id)));?></li>
					<li class="divider"></li>
					<?php
						}
					}
					if ($menuItem === 'edit') { ?>
						<li><?php echo $this->Html->link(__('View Thread'), array('controller' => 'threads', 'action' => 'view', h($thread_id)));?></li>
						<li class="active"><?php echo $this->Html->link(__('Edit Post'), array('controller' => 'threads', 'action' => 'view', h($id)));?></li>
						<li class="divider"></li>
					<?php
					}
					?>
					<li id='liindex'><?php echo $this->Html->link(__('List Threads'), array('controller' => 'threads', 'action' => 'index'));?></li>
					<li id='liadd'><a href = "<?php echo Configure::read('MISP.baseurl');?>/posts/add"><?php echo __('New Thread');?></a></li>
					<?php
				break;

				case 'tags': ?>
					<li id='liindexfav'><?php echo $this->Html->link(__('List Favourite Tags'), array('action' => 'index', true));?></li>
					<li id='liindex'><?php echo $this->Html->link(__('List Tags'), array('action' => 'index'));?></li>
				<?php
					if ($isAclTagEditor):
				?>
						<li id='liadd'><?php echo $this->Html->link(__('Add Tag'), array('action' => 'add'));?></li>
				<?php
					endif;
					if ($menuItem === 'edit'):
				?>
						<li class="active"><?php echo $this->Html->link(__('Edit Tag'), array('action' => 'edit'));?></li>
				<?php
					endif;
					if ($menuItem === 'viewGraph'):
						if (!empty($taxonomy)):
				?>
							<li><a href="<?php echo $baseurl; ?>/taxonomies/view/<?php echo h($taxonomy['Taxonomy']['id']); ?>"><?php echo __('View Taxonomy');?></a></li>
				<?php
						endif;
				?>
					<li id='liviewGraph'><a href="<?php echo $baseurl;?>/tags/viewGraph/<?php echo h($id); ?>"><?php echo __('View Correlation Graph');?></a></li>
				<?php
					endif;
				break;

				case 'taxonomies': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/taxonomies/index"><?php echo __('List Taxonomies');?></a></li>
					<?php if ($menuItem === 'view'): ?>
						<li id='liview'><a href=""><?php echo __('View Taxonomy');?></a></li>
						<li id='lidelete'><a class="useCursorPointer" onClick="deleteObject('taxonomies', 'delete', '<?php echo h($id); ?>', '<?php echo h($id); ?>');"><?php echo __('Delete Taxonomy');?></a></li>
					<?php
					endif;
					if ($isSiteAdmin):
					?>
						<li id='liupdate'><?php echo $this->Form->postLink('Update Taxonomies', array('controller' => 'taxonomies', 'action' => 'update'));?></li>
					<?php
					endif;
				break;

				case 'templates': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/templates/index"><?php echo __('List Templates');?></a></li>
					<?php if ($isSiteAdmin || $isAclTemplate): ?>
					<li id='liadd'><a href="<?php echo $baseurl;?>/templates/add"><?php echo __('Add Template');?></a></li>
					<?php
					endif;
					if (($menuItem === 'view' || $menuItem === 'edit')):
					?>
					<li id='liview'><a href="<?php echo $baseurl;?>/templates/view/<?php echo h($id); ?>"><?php echo __('View Template');?></a></li>
					<?php if ($mayModify): ?>
					<li id='liedit'><a href="<?php echo $baseurl;?>/templates/edit/<?php echo h($id); ?>"><?php echo __('Edit Template');?></a></li>
					<?php
					endif;
					endif;
				break;

				case 'feeds': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/feeds/index"><?php echo __('List Feeds');?></a></li>
					<li id='liadd'><a href="<?php echo $baseurl;?>/feeds/add"><?php echo __('Add Feed');?></a></li>
					<li id='liadd'><a href="<?php echo $baseurl;?>/feeds/importFeeds"><?php echo __('Import Feeds from JSON');?></a></li>
					<li id='licompare'><a href="<?php echo $baseurl;?>/feeds/compareFeeds"><?php echo __('Feed overlap analysis matrix');?></a></li>
					<li id='liexport'><a href="<?php echo $baseurl;?>/feeds/index.json" download="feed_index.json"><?php echo __('Export Feed settings');?></a></li>
					<?php if ($menuItem === 'edit'): ?>
						<li class="active"><a href="#"><?php echo __('Edit Feed');?></a></li>
					<?php elseif ($menuItem === 'previewIndex'): ?>
						<li id='lipreviewIndex'><a href="<?php echo $baseurl;?>/feeds/previewIndex/<?php echo h($feed['Feed']['id']); ?>"><?php echo __('PreviewIndex');?></a></li>
					<?php elseif ($menuItem === 'previewEvent'): ?>
						<li id='lipreviewEvent'><a href="<?php echo $baseurl;?>/feeds/previewEvent/<?php echo h($feed['Feed']['id']); ?>/<?php echo h($id);?>"><?php echo __('PreviewEvent');?></a></li>
					<?php endif;
				break;

				case 'news': ?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/news/index"><?php echo __('View News');?></a></li>
				<?php
					if ($isSiteAdmin):
				?>
						<li id='liadd'><a href="<?php echo $baseurl;?>/news/add"><?php echo __('Add News Item');?></a></li>
						<?php if ($menuItem === 'edit'): ?>
							<li class="active"><a href="#"><?php echo __('Edit News Item');?></a></li>
						<?php endif;
					endif;
				break;

				case 'galaxies':
				?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/galaxies/index"><?php echo __('List Galaxies');?></a></li>
				<?php
					if ($isSiteAdmin):
				?>
						<li><?php echo $this->Form->postLink(__('Update Galaxies'), array('controller' => 'galaxies', 'action' => 'update'), null, __('Are you sure you want to reimport all galaxies from the submodule?')); ?></li>
				<?php
					endif;
					if ($menuItem === 'viewGraph' || $menuItem === 'view_cluster'): ?>
						<li><a href="<?php echo $baseurl;?>/galaxies/view/<?php echo h($galaxy_id); ?>"><?php echo __('View Galaxy');?></a></li>
						<li id='liview_cluster'><a href="<?php echo $baseurl;?>/galaxy_clusters/view/<?php echo h($id); ?>"><?php echo __('View Cluster');?></a></li>
						<li id='liviewGraph'><a href="<?php echo $baseurl;?>/galaxies/viewGraph/<?php echo h($id); ?>"><?php echo __('View Correlation Graph');?></a></li>
				<?php
					endif;

					if ($menuItem === 'view'):
				?>
						<li class="active"><a href="#"><?php echo __('View Galaxy');?></a></li>
				<?php
					endif;
				break;
				case 'objectTemplates':
				?>
					<li id='liindex'><a href="<?php echo $baseurl;?>/objectTemplates/index"><?php echo __('List Object Templates');?></a></li>
				<?php
					if ($isSiteAdmin):
				?>
					<li><?php echo $this->Form->postLink(__('Update Objects'), '/objectTemplates/update'); ?></li>
				<?php
					endif;
					if ($menuItem === 'view'):
				?>
						<li class="active"><a href="#"><?php echo __('View Object Template');?></a></li>
				<?php
					endif;
				break;
			}
		?>
	</ul>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#li<?php echo h($menuItem); ?>').addClass("active");
});
</script>
