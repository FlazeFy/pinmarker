<div class="login-bg">
    <div class="blur-tl"></div>
    <div class="blur-br"></div>
    <div class="login-wrap">
        <div class="text-center mb-4">
            <h1 class="brand-name">PinMarker</h1>
            <p class="brand-sub">Marks Your World</p>
        </div>
        <div class="card text-center">
            <h2 class="card-title">Welcome Back</h2>
            <p class="card-sub">Sign in to your account to continue exploring.</p>
            <hr>
            <form action="/LoginController/login" method="POST">
                <div class="field mb-3">
                    <label class="label" for="identity">Email or Username</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-user input-icon" id="icon-identity"></i>
                        <input type="text" id="identity" name="identity" class="input" placeholder="Enter your email">
                    </div>
                </div>
                <div class="field mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="label" for="password">Password</label>
                        <a href="/ForgotController" class="forgot">Forgot Password?</a>
                    </div>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon" id="icon-password"></i>
                        <input type="password" id="password" name="password" class="input" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="btn-primary btn-lg w-100">Sign In</button>
            </form>
            <div class="divider">
                <span>Or Continue With</span>
            </div>
            <div class="row g-2 mb-4">
                <div class="col-4 mx-auto">
                    <a href="/LoginController/google" class="social-btn">
                        <i class="fa-brands fa-google"></i>
                        <span>Google</span>
                    </a>
                </div>
            </div>
            <p>Don't have an account? <a href="/RegisterController" class="link-primary">Register Now</a></p>
        </div>
        <div class="util-footer">
            <a href="/HelpController" class="help-btn">
                <i class="fa-regular fa-circle-question"></i> Help Center
            </a>
            <div class="util-links">
                <a href="/PrivacyController">Privacy Policy</a>
                <span>•</span>
                <a href="/TermsController">Terms of Service</a>
            </div>
        </div>
    </div>
</div>

<style>
    .login-bg {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--spaceXLG);
        background-color: var(--containerColor);
        background-image:
            radial-gradient(at 0% 0%, rgba(99,91,255,.05) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(99,91,255,.07) 0px, transparent 50%);
        position: relative;
        overflow: hidden;
    }
    .blur-tl {
        position: fixed; top: 48px; left: 48px;
        width: 140px; height: 140px;
        background: rgba(99,91,255,.06);
        border-radius: 50%; filter: blur(60px);
        pointer-events: none; z-index: 0;
    }
    .blur-br {
        position: fixed; bottom: 48px; right: 48px;
        width: 240px; height: 240px;
        background: rgba(93,93,107,.05);
        border-radius: 50%; filter: blur(80px);
        pointer-events: none; z-index: 0;
    }
    .login-wrap {
        width: 100%; max-width: 440px;
        position: relative; z-index: 1;
    }

    /* Brand */
    .brand-icon {
        font-size: 36px;
        color: var(--primaryColor);
        display: block;
        margin-bottom: var(--spaceSM);
    }
    .brand-name {
        font-size: clamp(24px, 4vw, 30px);
        font-weight: 800;
        color: var(--secondaryColor);
        letter-spacing: -.02em;
        margin-bottom: 4px;
    }
    .brand-sub {
        font-size: var(--textXMD);
        color: #464555;
        margin: 0;
    }

    /* Card */
    .card {
        background: #fff;
        border-radius: var(--roundedXLG);
        border: 1px solid rgba(199,196,216,.35);
        padding: var(--spaceXLG);
        box-shadow: 0 20px 40px rgba(0,0,0,.04);
    }
    .card-title { 
        font-size: 22px; font-weight: 700; color: var(--secondaryColor); margin-bottom: 6px; 
    }
    .card-sub { 
        font-size: var(--textXMD); 
        color: #464555; margin: 0; 
    }

    /* Fields */
    .label {
        display: block;
        font-size: var(--textXSM);
        font-weight: 700;
        letter-spacing: .04em;
        color: #464555;
        margin-bottom: 6px;
        text-transform: uppercase;
    }
    .input-wrap { 
        position: relative; 
    }
    .input-icon {
        position: absolute;
        left: 14px; top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: #777587;
        transition: color .2s;
        pointer-events: none;
    }
    .input {
        width: 100%;
        padding: 12px 14px 12px 40px;
        background: #f2f3f7;
        border: 1.5px solid #e1e2e6;
        border-radius: var(--roundedMD);
        font-size: var(--textXMD);
        font-family: inherit;
        color: var(--secondaryColor);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .input:focus {
        border-color: var(--primaryColor);
        box-shadow: 0 0 0 3px rgba(99,91,255,.12);
        background: #fff;
    }
    .input.focused + .input-icon, .input-icon.active { 
        color: var(--primaryColor); 
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

    /* Divider */
    .divider {
        position: relative;
        text-align: center;
        margin: var(--spaceXLG) 0;
    }
    .divider::before {
        content: '';
        position: absolute;
        top: 50%; left: 0; right: 0;
        height: 1px;
        background: rgba(199,196,216,.5);
    }
    .divider span {
        position: relative;
        background: #fff;
        padding: 0 14px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .06em;
        color: #777587;
    }

    /* Social */
    .social-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: var(--spaceMD) var(--spaceSM);
        background: #f2f3f7;
        border: 1.5px solid #e1e2e6;
        border-radius: var(--roundedMD);
        text-decoration: none;
        color: #464555;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .05em;
        transition: all .2s;
    }
    .social-btn i { font-size: 18px; }
    .social-btn:hover {
        background: var(--primaryLightColor);
        border-color: var(--primaryColor);
        color: var(--primaryColor);
    }

    /* Card footer */
    .card-footer {
        border-top: 1px solid rgba(199,196,216,.3);
        padding-top: var(--spaceXMD);
        text-align: center;
        margin-top: var(--spaceXMD);
    }
    .card-footer p { font-size: var(--textXMD); color: #464555; margin: 0; }
    .link-primary { color: var(--primaryColor); font-weight: 700; text-decoration: none; }
    .link-primary:hover { text-decoration: underline; }

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
    .help-btn:hover { background: #e2e1f1; color: var(--primaryColor); }
    .util-links {
        display: flex;
        align-items: center;
        gap: var(--spaceSM);
        font-size: var(--textXSM);
        color: #777587;
    }
    .util-links a { color: #777587; text-decoration: none; }
    .util-links a:hover { color: #464555; }
</style>
