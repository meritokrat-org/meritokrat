<?php

namespace WebUI\Layout;

use App\Component\ComponentInterface;
use App\Traits\CreatableTrait;

class LoginFormModal implements ComponentInterface
{
    use CreatableTrait;

    public function render()
    {
        $t = static function ($phrase, $vars = [], $lang = null) {
            return t($phrase, $vars, $lang);
        };

        $ids = [
            'modal' => 'LoginFormModal',
            'form'  => 'LoginForm',
        ];

        return <<<HTML
<a class="nav-link" href="javascript:" data-bs-toggle="modal" data-bs-target="#{$ids['modal']}">
    <i class="fas fa-sign-in-alt"></i>
    {$t('Вход')}
</a>
<form novalidate name="{$ids['form']}" method="post" action="/sign/in" onsubmit="return false;">
    <div class="modal fade" id="{$ids['modal']}" tabindex="-1" aria-labelledby="{$ids['modal']}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 333px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{$ids['modal']}Label">{$t('Вход в сеть')}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                        <label for="email">{$t('E-почта')}</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <label for="password">{$t('Пароль')}</label>
                    </div>
                    <div class="alert alert-danger m-0 mt-3 d-none" role="alert"></div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary w-100">{$t('Вход')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    (() => {
        const form = document.forms['{$ids['form']}'];
        const alert = form.querySelector('.alert');
        
        form.addEventListener('submit', () => {
            useFetch()
        });
        
        async function useFetch() {
            const { action, method } = form;
            const body = new FormData(form);
            try {
                const response = await fetch(action, { method, body });
                const data = await response.json();
                
                if (response.status === 401) {
                   throw data.error;
                }
                
                form.classList.add('was-validated');
                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                form
                        .querySelectorAll('input.form-control')
                        .forEach(e => e.classList.add('is-invalid'));
                alert.classList.remove('d-none');
                alert.textContent = error;
            }
        }
    }).apply(null);
</script>
HTML;
    }
}
