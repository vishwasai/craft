/** global: Craft */
/** global: Garnish */
/**
 * Elevated Session Manager
 */
Craft.ElevatedSessionManager = Garnish.Base.extend(
  {
    fetchingTimeout: false,

    passwordModal: null,
    $passwordInput: null,
    $passwordSpinner: null,
    $submitBtn: null,
    $errorPara: null,

    callback: null,

    /**
     * Requires that the user has an elevated session.
     *
     * @param {function} callback The callback function that should be called once the user has an elevated session
     */
    requireElevatedSession: function (callback) {
      this.callback = callback;

      // Check the time remaining on the user's elevated session (if any)
      this.fetchingTimeout = true;

      Craft.postActionRequest(
        'users/get-elevated-session-timeout',
        (response, textStatus) => {
          this.fetchingTimeout = false;

          if (textStatus === 'success') {
            // Is there still enough time left or has it been disabled?
            if (
              response.timeout === false ||
              response.timeout >=
                Craft.ElevatedSessionManager.minSafeElevatedSessionTimeout
            ) {
              this.callback();
            } else {
              // Show the password modal
              this.showPasswordModal();
            }
          }
        }
      );
    },

    showPasswordModal: function () {
      if (!this.passwordModal) {
        var $passwordModal = $(
            '<form id="elevatedsessionmodal" class="modal secure fitted"/>'
          ),
          $body = $(
            '<div class="body"><p>' +
              Craft.t('app', 'Enter your password to continue.') +
              '</p></div>'
          ).appendTo($passwordModal),
          $inputContainer = $('<div class="inputcontainer">').appendTo($body),
          $inputsFlexContainer = $('<div class="flex"/>').appendTo(
            $inputContainer
          ),
          $passwordContainer = $('<div class="flex-grow"/>').appendTo(
            $inputsFlexContainer
          ),
          $buttonContainer = $('<td/>').appendTo($inputsFlexContainer),
          $passwordWrapper = $('<div class="passwordwrapper"/>').appendTo(
            $passwordContainer
          );

        this.$passwordInput = $(
          '<input type="password" class="text password fullwidth" placeholder="' +
            Craft.t('app', 'Password') +
            '" autocomplete="current-password"/>'
        ).appendTo($passwordWrapper);
        this.$passwordSpinner = $('<div class="spinner hidden"/>').appendTo(
          $inputContainer
        );
        this.$submitBtn = $('<button/>', {
          type: 'submit',
          class: 'btn submit disabled',
          text: Craft.t('app', 'Submit'),
        }).appendTo($buttonContainer);
        this.$errorPara = $('<p class="error"/>').appendTo($body);

        this.passwordModal = new Garnish.Modal($passwordModal, {
          closeOtherModals: false,
          onFadeIn: () => {
            setTimeout(this.focusPasswordInput.bind(this), 100);
          },
          onFadeOut: () => {
            this.$passwordInput.val('');
          },
        });

        new Craft.PasswordInput(this.$passwordInput, {
          onToggleInput: ($newPasswordInput) => {
            this.$passwordInput = $newPasswordInput;
          },
        });

        this.addListener(this.$passwordInput, 'input', 'validatePassword');
        this.addListener($passwordModal, 'submit', 'submitPassword');
      } else {
        this.passwordModal.show();
      }
    },

    focusPasswordInput: function () {
      if (!Garnish.isMobileBrowser(true)) {
        this.$passwordInput.trigger('focus');
      }
    },

    validatePassword: function () {
      if (this.$passwordInput.val().length >= 6) {
        this.$submitBtn.removeClass('disabled');
        return true;
      } else {
        this.$submitBtn.addClass('disabled');
        return false;
      }
    },

    submitPassword: function (ev) {
      if (ev) {
        ev.preventDefault();
      }

      if (!this.validatePassword()) {
        return;
      }

      this.$passwordSpinner.removeClass('hidden');
      this.clearLoginError();

      var data = {
        currentPassword: this.$passwordInput.val(),
      };

      Craft.postActionRequest(
        'users/start-elevated-session',
        data,
        (response, textStatus) => {
          this.$passwordSpinner.addClass('hidden');

          if (textStatus === 'success') {
            if (response.success) {
              this.passwordModal.hide();
              this.callback();
            } else {
              this.showPasswordError(
                response.message || Craft.t('app', 'Incorrect password.')
              );
              Garnish.shake(this.passwordModal.$container);
              this.focusPasswordInput();
            }
          } else {
            this.showPasswordError();
          }
        }
      );
    },

    showPasswordError: function (error) {
      if (error === null || typeof error === 'undefined') {
        error = Craft.t('app', 'A server error occurred.');
      }

      this.$errorPara.text(error);
      this.passwordModal.updateSizeAndPosition();
    },

    clearLoginError: function () {
      this.showPasswordError('');
    },
  },
  {
    minSafeElevatedSessionTimeout: 5,
  }
);

// Instantiate it
Craft.elevatedSessionManager = new Craft.ElevatedSessionManager();
