<div class="login-bg">
    <?php $this->load->view("login/form"); ?>
</div>

<style>
    .login-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--spaceXLG);
        position: relative;
        overflow: hidden;
    }
    .login-wrap {
        width: 100%; max-width: 440px;
        position: relative; z-index: 1;
    }

    .forgot {
        font-size: var(--textXSM);
        font-weight: 700;
        color: var(--primaryColor);
        text-decoration: none;
        letter-spacing: .03em;
    }
    .forgot:hover { 
        text-decoration: underline; 
    }

    .link-primary { 
        color: var(--primaryColor); 
        font-weight: 700; 
        text-decoration: none; 
    }
    .link-primary:hover { 
        text-decoration: underline; 
    }

    /* Util footer */
    .util-footer {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--spaceMD);
        margin-top: var(--spaceXLG);
    }
    .help-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: #e7e8ec;
        color: #464555;
        border-radius: var(--roundedJumbo);
        font-size: var(--textXSM);
        font-weight: 700;
        text-decoration: none;
        letter-spacing: .03em;
        transition: all .2s;
    }
    .help-btn:hover { 
        background: #e2e1f1; 
        color: var(--primaryColor); 
    }
    .util-links {
        display: flex;
        align-items: center;
        gap: var(--spaceSM);
        font-size: var(--textXSM);
        color: #777587;
    }
    .util-links a { 
        color: #777587; 
        text-decoration: none; 
    }
    .util-links a:hover { 
        color: #464555; 
    }
</style>
