<div>
    <h2 class="text-2xl font-bold mb-2">Resource Operations</h2>
    <p class="text-neutral-600 dark:text-neutral-400 mb-6">You can easily make different kind of operations on this resource.</p>

    @can('update', $resource)
        <div x-data="resourceOperations({
            currentProjectId: {{ $resource->environment->project->id }},
            currentEnvironmentId: {{ $resource->environment->id }},
            servers: @js($servers->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'ip' => $s->ip,
                'destinations' => $s->destinations()->map(fn($d) => [
                    'id' => $d->id,
                    'name' => $d->name,
                    'server_id' => $s->id,
                ]),
            ])),
            projects: @js($projects->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'environments' => $p->environments->map(fn($e) => [
                    'id' => $e->id,
                    'name' => $e->name,
                    'project_id' => $p->id,
                ]),
            ])),
        })">
            <!-- Clone Resource Section -->
            <section class="mb-8" aria-labelledby="clone-heading">
                <h3 id="clone-heading" class="text-xl font-semibold mb-2">Clone Resource</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">Duplicate this resource to another server or network destination.</p>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="w-full">
                            <label class="flex gap-1 items-center mb-1 text-sm font-medium">
                                Select Server
                                <x-helper helper="Choose the target server for cloning" />
                            </label>
                            <select
                                x-model="selectedCloneServer"
                                @change="selectedCloneDestination = null"
                                class="select w-full"
                                aria-label="Select server for cloning"
                                aria-describedby="clone-server-helper">
                                <option value="">Choose a server...</option>
                                <template x-for="server in servers" :key="server.id">
                                    <option :value="server.id" x-text="`${server.name} (${server.ip})`"></option>
                                </template>
                            </select>
                        </div>

                        <div class="w-full">
                            <label class="flex gap-1 items-center mb-1 text-sm font-medium">
                                Select Network Destination
                                <x-helper helper="Choose the destination on the selected server" />
                            </label>
                            <select
                                x-model="selectedCloneDestination"
                                :disabled="!selectedCloneServer || availableDestinations.length === 0"
                                class="select w-full"
                                aria-label="Select network destination"
                                aria-describedby="clone-destination-helper">
                                <option value="" x-text="availableDestinations.length === 0 && selectedCloneServer ? 'No destinations available' : 'Choose a destination...'"></option>
                                <template x-for="destination in availableDestinations" :key="destination.id">
                                    <option :value="destination.id" x-text="destination.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div x-show="selectedCloneDestination" x-cloak class="mt-4">
                        <x-forms.button
                            isHighlighted
                            @click="cloneModalOpen = true"
                            canGate="update"
                            :canResource="$resource"
                            aria-label="Open clone confirmation modal">
                            Clone Resource
                        </x-forms.button>
                        <div class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                            All configurations will be duplicated to the selected destination. The running application won't be touched.
                        </div>

                        <template x-teleport="body">
                            <div x-show="cloneModalOpen" x-cloak
                                class="fixed top-0 left-0 z-99 flex items-center justify-center w-screen h-screen p-4"
                                @keydown.escape.window="cloneModalOpen = false"
                                role="dialog"
                                aria-modal="true"
                                aria-labelledby="clone-modal-title">
                                <div x-show="cloneModalOpen" class="absolute inset-0 w-full h-full bg-black/20 backdrop-blur-xs"
                                    @click="cloneModalOpen = false"
                                    aria-hidden="true"></div>
                                <div x-show="cloneModalOpen" x-trap.inert.noscroll="cloneModalOpen"
                                    x-transition:enter="ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-100"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 -translate-y-2 sm:scale-95"
                                    class="relative w-full border rounded-sm min-w-full lg:min-w-[36rem] max-w-[48rem] max-h-[calc(100vh-2rem)] bg-neutral-100 border-neutral-400 dark:bg-base dark:border-coolgray-300 flex flex-col">
                                    <div class="flex justify-between items-center py-6 px-7 shrink-0">
                                        <h3 id="clone-modal-title" class="pr-8 text-2xl font-bold">Confirm Clone Operation</h3>
                                        <button @click="cloneModalOpen = false"
                                            class="flex absolute top-2 right-2 justify-center items-center w-8 h-8 rounded-full dark:text-white hover:bg-coolgray-300"
                                            aria-label="Close modal">
                                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="relative w-auto overflow-y-auto px-7 pb-6">
                                        <p class="text-neutral-600 dark:text-neutral-400">
                                            Are you sure you want to clone this resource to <strong x-text="selectedDestinationName"></strong>?
                                        </p>
                                        <p class="mt-4 text-sm text-neutral-600 dark:text-neutral-400">
                                            All configurations will be duplicated to the selected destination. The running application won't be touched.
                                        </p>
                                    </div>
                                    <div class="flex gap-4 justify-end px-7 py-6 border-t border-neutral-400 dark:border-coolgray-300">
                                        <x-forms.button @click="cloneModalOpen = false">Cancel</x-forms.button>
                                        <x-forms.button
                                            isHighlighted
                                            @click="
                                                cloneModalOpen = false;
                                                $wire.cloneTo(selectedCloneDestination);
                                            ">
                                            Clone Resource
                                        </x-forms.button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>

            <!-- Move Resource Section -->
            <section aria-labelledby="move-heading">
                <h3 id="move-heading" class="text-xl font-semibold mb-2">Move Resource</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">Transfer this resource between projects and environments.</p>

                @if ($projects->count() > 0)
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="w-full">
                                <label class="flex gap-1 items-center mb-1 text-sm font-medium">
                                    Select Target Project
                                    <x-helper helper="Choose the target project" />
                                </label>
                                <select
                                    x-model="selectedMoveProject"
                                    @change="selectedMoveEnvironment = null"
                                    class="select w-full"
                                    aria-label="Select target project"
                                    aria-describedby="move-project-helper">
                                    <option value="">Choose a project...</option>
                                    <template x-for="project in projects" :key="project.id">
                                        <option :value="project.id" x-text="project.name + (project.id === currentProjectId ? ' (current)' : '')"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="w-full">
                                <label class="flex gap-1 items-center mb-1 text-sm font-medium">
                                    Select Target Environment
                                    <x-helper helper="Current environment is excluded" />
                                </label>
                                <select
                                    x-model="selectedMoveEnvironment"
                                    :disabled="!selectedMoveProject || availableEnvironments.length === 0"
                                    class="select w-full"
                                    aria-label="Select target environment"
                                    aria-describedby="move-environment-helper">
                                    <option value="" x-text="availableEnvironments.length === 0 && isCurrentProjectSelected ? 'No other environments available' : 'Choose an environment...'"></option>
                                    <template x-for="environment in availableEnvironments" :key="environment.id">
                                        <option :value="environment.id" x-text="environment.name + (environment.id === currentEnvironmentId ? ' (current)' : '')"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div x-show="selectedMoveEnvironment" x-cloak class="mt-4">
                            <x-forms.button
                                isHighlighted
                                @click="moveModalOpen = true"
                                canGate="update"
                                :canResource="$resource"
                                aria-label="Open move confirmation modal">
                                Move Resource
                            </x-forms.button>
                            <div class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                                All configurations will be moved to the selected environment. The running application won't be touched.
                            </div>

                            <template x-teleport="body">
                                <div x-show="moveModalOpen" x-cloak
                                    class="fixed top-0 left-0 z-99 flex items-center justify-center w-screen h-screen p-4"
                                    @keydown.escape.window="moveModalOpen = false"
                                    role="dialog"
                                    aria-modal="true"
                                    aria-labelledby="move-modal-title">
                                    <div x-show="moveModalOpen" class="absolute inset-0 w-full h-full bg-black/20 backdrop-blur-xs"
                                        @click="moveModalOpen = false"
                                        aria-hidden="true"></div>
                                    <div x-show="moveModalOpen" x-trap.inert.noscroll="moveModalOpen"
                                        x-transition:enter="ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="ease-in duration-100"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 -translate-y-2 sm:scale-95"
                                        class="relative w-full border rounded-sm min-w-full lg:min-w-[36rem] max-w-[48rem] max-h-[calc(100vh-2rem)] bg-neutral-100 border-neutral-400 dark:bg-base dark:border-coolgray-300 flex flex-col">
                                        <div class="flex justify-between items-center py-6 px-7 shrink-0">
                                            <h3 id="move-modal-title" class="pr-8 text-2xl font-bold">Confirm Move Operation</h3>
                                            <button @click="moveModalOpen = false"
                                                class="flex absolute top-2 right-2 justify-center items-center w-8 h-8 rounded-full dark:text-white hover:bg-coolgray-300"
                                                aria-label="Close modal">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="relative w-auto overflow-y-auto px-7 pb-6">
                                            <p class="text-neutral-600 dark:text-neutral-400">
                                                Are you sure you want to move this resource to <strong x-text="selectedProjectName"></strong> / <strong x-text="selectedEnvironmentName"></strong>?
                                            </p>
                                            <p class="mt-4 text-sm text-neutral-600 dark:text-neutral-400">
                                                All configurations will be moved to the selected environment. The running application won't be touched.
                                            </p>
                                        </div>
                                        <div class="flex gap-4 justify-end px-7 py-6 border-t border-neutral-400 dark:border-coolgray-300">
                                            <x-forms.button @click="moveModalOpen = false">Cancel</x-forms.button>
                                            <x-forms.button
                                                isHighlighted
                                                @click="
                                                    moveModalOpen = false;
                                                    $wire.moveTo(selectedMoveEnvironment);
                                                ">
                                                Move Resource
                                            </x-forms.button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                @else
                    <x-callout type="info" title="No Projects Available">
                        No other projects available for moving this resource.
                    </x-callout>
                @endif
            </section>
        </div>
    @else
        <x-callout type="warning" title="Access Restricted">
            You don't have permission to perform resource operations. Contact your team administrator to request access.
        </x-callout>
    @endcan

    <script>
        function resourceOperations(data) {
            return {
                selectedCloneServer: null,
                selectedCloneDestination: null,
                selectedMoveProject: null,
                selectedMoveEnvironment: null,
                cloneModalOpen: false,
                moveModalOpen: false,
                ...data,
                get availableDestinations() {
                    if (!this.selectedCloneServer) return [];
                    const server = this.servers.find(s => s.id == this.selectedCloneServer);
                    return server ? server.destinations : [];
                },
                get selectedDestinationName() {
                    if (!this.selectedCloneDestination) return '';
                    const destination = this.availableDestinations.find(d => d.id == this.selectedCloneDestination);
                    return destination ? destination.name : '';
                },
                get availableEnvironments() {
                    if (!this.selectedMoveProject) return [];
                    const project = this.projects.find(p => p.id == this.selectedMoveProject);
                    if (!project) return [];
                    return project.environments.filter(e => {
                        if (project.id === this.currentProjectId) {
                            return e.id !== this.currentEnvironmentId;
                        }
                        return true;
                    });
                },
                get selectedEnvironmentName() {
                    if (!this.selectedMoveEnvironment) return '';
                    const environment = this.availableEnvironments.find(e => e.id == this.selectedMoveEnvironment);
                    return environment ? environment.name : '';
                },
                get selectedProjectName() {
                    if (!this.selectedMoveProject) return '';
                    const project = this.projects.find(p => p.id == this.selectedMoveProject);
                    return project ? project.name : '';
                },
                get isCurrentProjectSelected() {
                    return this.selectedMoveProject == this.currentProjectId;
                },
            };
        }
    </script>
</div>
