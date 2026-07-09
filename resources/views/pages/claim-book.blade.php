@extends('layouts.app')

@section('title', __('messages.nav.claim_book') . ' | Dos Aguas')

@section('content')

    <main class="max-w-container-max mx-auto px-margin-edge py-20 font-body"
          x-data="{
              docType: 'DNI',
              docNumber: '',
              fullName: '',
              email: '',
              phone: '',
              address: '',
              isMinor: false,
              repName: '',
              repDocType: 'DNI',
              repDocNumber: '',
              claimType: 'reclamacion',
              claimedAmount: '',
              description: '',
              details: '',
              request: '',
              errors: {},
              successMessage: '',
              claimCode: '',
              loading: false,

              submitClaim() {
                  this.loading = true;
                  this.errors = {};
                  this.successMessage = '';
                  this.claimCode = '';

                  fetch('{{ route('claim-book') }}', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                      },
                      body: JSON.stringify({
                          document_type: this.docType,
                          document_number: this.docNumber,
                          full_name: this.fullName,
                          email: this.email,
                          phone: this.phone,
                          address: this.address,
                          is_minor: this.isMinor ? 1 : 0,
                          representative_name: this.isMinor ? this.repName : null,
                          representative_document_type: this.isMinor ? this.repDocType : null,
                          representative_document_number: this.isMinor ? this.repDocNumber : null,
                          type: this.claimType,
                          claimed_amount: this.claimedAmount ? this.claimedAmount : null,
                          product_service_description: this.description,
                          claim_details: this.details,
                          consumer_request: this.request
                      })
                  })
                  .then(res => {
                      return res.json().then(data => ({ status: res.status, body: data }));
                  })
                  .then(res => {
                      this.loading = false;
                      if (res.status === 201) {
                          this.successMessage = res.body.message;
                          this.claimCode = res.body.claim_code;
                          // Reset form variables
                          this.docNumber = '';
                          this.fullName = '';
                          this.email = '';
                          this.phone = '';
                          this.address = '';
                          this.isMinor = false;
                          this.repName = '';
                          this.repDocNumber = '';
                          this.claimedAmount = '';
                          this.description = '';
                          this.details = '';
                          this.request = '';
                      } else if (res.status === 422) {
                          this.errors = res.body.errors || {};
                      } else {
                          alert('Error al registrar la reclamación. Por favor intente de nuevo.');
                      }
                  })
                  .catch(err => {
                      this.loading = false;
                      alert('Error de red. Verifique su conexión.');
                  });
              }
          }">
        
        <!-- Breadcrumbs -->
        <nav class="mb-12 flex items-center gap-2 font-label-caps text-[10px] tracking-widest text-outline">
            <a class="hover:text-primary transition-colors duration-300" href="{{ route('home') }}">Home</a>
            <span class="text-[8px] opacity-40">/</span>
            <span class="text-on-surface font-bold">{{ __('messages.nav.claim_book') }}</span>
        </nav>

        <div class="max-w-3xl mx-auto text-center space-y-6 mb-16">
            <span class="font-label-caps text-xs text-primary tracking-[0.3em] uppercase block font-bold">
                {{ app()->getLocale() == 'es' ? 'LIBRO DE RECLAMACIONES DIGITAL' : 'DIGITAL COMPLAINTS BOOK' }}
            </span>
            <h1 class="font-headline text-3xl md:text-4xl font-bold leading-tight">
                {{ __('messages.claims.title') }}
            </h1>
            <p class="font-body text-xs text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                {{ __('messages.claims.subtitle') }}
            </p>
            <div class="w-16 h-px bg-primary mx-auto"></div>
        </div>

        <div class="max-w-3xl mx-auto bg-[#161616] border border-outline-variant/10 p-8 md:p-12 space-y-8">
            
            <!-- Success Message and Claim Code -->
            <div x-show="successMessage" x-transition class="p-6 bg-leaf-green/10 border border-leaf-green/30 text-leaf-green text-xs font-body space-y-3">
                <p class="font-bold text-sm" x-text="successMessage"></p>
                <p class="text-[11px] text-on-surface-variant">
                    {{ __('messages.claims.code') }}: <span class="font-bold text-on-surface text-sm underline" x-text="claimCode"></span>
                </p>
            </div>

            <form @submit.prevent="submitClaim()" class="space-y-8 text-xs font-body">
                
                <!-- Section 1: Customer Details -->
                <div class="space-y-6">
                    <h3 class="font-headline text-base font-bold text-primary uppercase border-b border-outline-variant/10 pb-2">
                        1. {{ app()->getLocale() == 'es' ? 'Identificación del Consumidor' : 'Consumer Identification' }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Document Type -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.doc_type') }} *</label>
                            <select x-model="docType" required
                                    class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs">
                                <option value="DNI">DNI</option>
                                <option value="CE">C.E.</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="RUC">RUC</option>
                            </select>
                        </div>
                        
                        <!-- Document Number -->
                        <div class="md:col-span-2 flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.doc_number') }} *</label>
                            <input type="text" x-model="docNumber" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.document_number">
                                <span class="text-error text-[10px]" x-text="errors.document_number[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.full_name') }} *</label>
                        <input type="text" x-model="fullName" required
                               class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                        <template x-if="errors.full_name">
                            <span class="text-error text-[10px]" x-text="errors.full_name[0]"></span>
                        </template>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.checkout.email') }} *</label>
                            <input type="email" x-model="email" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.email">
                                <span class="text-error text-[10px]" x-text="errors.email[0]"></span>
                            </template>
                        </div>
                        
                        <!-- Phone -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.checkout.phone') }} *</label>
                            <input type="text" x-model="phone" required
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.phone">
                                <span class="text-error text-[10px]" x-text="errors.phone[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.checkout.address') }} *</label>
                        <input type="text" x-model="address" required
                               class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                        <template x-if="errors.address">
                            <span class="text-error text-[10px]" x-text="errors.address[0]"></span>
                        </template>
                    </div>

                    <!-- Is Minor Checkbox Toggle -->
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" x-model="isMinor" 
                               class="w-4 h-4 rounded-none bg-transparent border-outline/30 text-primary focus:ring-0 checked:bg-primary checked:border-primary" />
                        <span class="font-bold uppercase tracking-wider text-[10px] text-on-surface-variant">{{ __('messages.claims.is_minor') }}</span>
                    </label>

                    <!-- Representative details (show if minor checked) -->
                    <div x-show="isMinor" x-transition class="space-y-6 bg-[#1c1b1b] border border-outline-variant/10 p-6">
                        <h4 class="font-label-caps text-[9px] tracking-wider text-primary font-bold uppercase mb-4">
                            {{ app()->getLocale() == 'es' ? 'Detalles del Representante Legal' : 'Legal Representative Details' }}
                        </h4>

                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.rep_name') }} *</label>
                            <input type="text" x-model="repName" :required="isMinor"
                                   class="bg-[#131313] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.representative_name">
                                <span class="text-error text-[10px]" x-text="errors.representative_name[0]"></span>
                            </template>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Rep Doc Type -->
                            <div class="flex flex-col gap-2">
                                <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.rep_doc_type') }} *</label>
                                <select x-model="repDocType" :required="isMinor"
                                        class="bg-[#131313] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs">
                                    <option value="DNI">DNI</option>
                                    <option value="CE">C.E.</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                            
                            <!-- Rep Doc Number -->
                            <div class="md:col-span-2 flex flex-col gap-2">
                                <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.rep_doc_num') }} *</label>
                                <input type="text" x-model="repDocNumber" :required="isMinor"
                                       class="bg-[#131313] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                                <template x-if="errors.representative_document_number">
                                    <span class="text-error text-[10px]" x-text="errors.representative_document_number[0]"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Product / Service Details -->
                <div class="space-y-6">
                    <h3 class="font-headline text-base font-bold text-primary uppercase border-b border-outline-variant/10 pb-2">
                        2. {{ app()->getLocale() == 'es' ? 'Detalle del Bien Contratado' : 'Contracted Good Details' }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Claim Type (Radio buttons) -->
                        <div class="md:col-span-2 flex flex-col gap-3">
                            <span class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.claim_type') }} *</span>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="claimType" value="reclamacion"
                                           class="w-4 h-4 bg-transparent border-outline/30 text-primary focus:ring-0 checked:bg-primary" />
                                    <span>{{ app()->getLocale() == 'es' ? 'Reclamación' : 'Appeal' }}</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" x-model="claimType" value="queja"
                                           class="w-4 h-4 bg-transparent border-outline/30 text-primary focus:ring-0 checked:bg-primary" />
                                    <span>{{ app()->getLocale() == 'es' ? 'Queja' : 'Complaint' }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Claimed Amount -->
                        <div class="flex flex-col gap-2">
                            <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.amount') }}</label>
                            <input type="number" step="0.01" x-model="claimedAmount"
                                   class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs" />
                            <template x-if="errors.claimed_amount">
                                <span class="text-error text-[10px]" x-text="errors.claimed_amount[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Type Description messages -->
                    <div class="text-[10px] text-outline leading-relaxed italic bg-[#1c1b1b] p-4 border border-outline-variant/5">
                        <p x-show="claimType === 'reclamacion'">◆ {{ __('messages.claims.type_reclamacion') }}</p>
                        <p x-show="claimType === 'queja'">◆ {{ __('messages.claims.type_queja') }}</p>
                    </div>

                    <!-- Product description text -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.desc') }} *</label>
                        <textarea x-model="description" required rows="3"
                                  class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs resize-none"></textarea>
                        <template x-if="errors.product_service_description">
                            <span class="text-error text-[10px]" x-text="errors.product_service_description[0]"></span>
                        </template>
                    </div>
                </div>

                <!-- Section 3: Complaint details -->
                <div class="space-y-6">
                    <h3 class="font-headline text-base font-bold text-primary uppercase border-b border-outline-variant/10 pb-2">
                        3. {{ app()->getLocale() == 'es' ? 'Detalle de la Disconformidad' : 'Details of Disagreement' }}
                    </h3>

                    <!-- Details of Claim -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.details') }} *</label>
                        <textarea x-model="details" required rows="5"
                                  class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs resize-none"></textarea>
                        <template x-if="errors.claim_details">
                            <span class="text-error text-[10px]" x-text="errors.claim_details[0]"></span>
                        </template>
                    </div>

                    <!-- Consumer Request -->
                    <div class="flex flex-col gap-2">
                        <label class="font-label-caps text-[9px] tracking-wider text-outline font-bold uppercase">{{ __('messages.claims.request') }} *</label>
                        <textarea x-model="request" required rows="4"
                                  class="bg-[#1c1b1b] border border-outline-variant/30 text-on-surface py-3 px-4 focus:ring-0 focus:outline-none focus:border-primary text-xs resize-none"></textarea>
                        <template x-if="errors.consumer_request">
                            <span class="text-error text-[10px]" x-text="errors.consumer_request[0]"></span>
                        </template>
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="pt-4 border-t border-outline-variant/10">
                    <button type="submit" :disabled="loading"
                            class="bg-primary text-on-primary px-12 py-4 font-label-caps text-xs tracking-widest hover:bg-secondary hover:text-on-secondary disabled:bg-surface-container-high transition-all duration-300 font-bold flex items-center gap-2">
                        <span x-show="loading" class="w-3.5 h-3.5 border-2 border-on-primary border-t-transparent rounded-full animate-spin"></span>
                        <span x-text="loading ? '...' : '{{ __('messages.claims.submit') }}'"></span>
                    </button>
                </div>

            </form>
        </div>

    </main>

@endsection
