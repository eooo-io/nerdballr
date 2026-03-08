import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { LoginScene } from './LoginScene';
import { login, register } from '@/api/auth';
import { useAuthStore } from '@/stores/authStore';
import './LoginPage.css';

type Tab = 'login' | 'register';

export function LoginPage() {
  const [tab, setTab] = useState<Tab>('login');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [name, setName] = useState('');
  const [passwordConfirm, setPasswordConfirm] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [clock, setClock] = useState('--:--:--');

  const navigate = useNavigate();
  const setUser = useAuthStore((s) => s.setUser);

  useEffect(() => {
    const tick = () => setClock(new Date().toTimeString().slice(0, 8));
    tick();
    const id = setInterval(tick, 1000);
    return () => clearInterval(id);
  }, []);

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const user = await login({ email, password });
      setUser(user);
      navigate('/');
    } catch (err: unknown) {
      const msg = (err as { response?: { data?: { message?: string } } })?.response?.data?.message;
      setError(msg || 'Authentication failed.');
    } finally {
      setLoading(false);
    }
  };

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const user = await register({
        name,
        email,
        password,
        password_confirmation: passwordConfirm,
      });
      setUser(user);
      navigate('/');
    } catch (err: unknown) {
      const data = (err as { response?: { data?: { message?: string; errors?: Record<string, string[]> } } })?.response?.data;
      if (data?.errors) {
        setError(Object.values(data.errors).flat()[0]);
      } else {
        setError(data?.message || 'Registration failed.');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleGuest = () => {
    navigate('/');
  };

  return (
    <>
      <LoginScene />

      {/* CRT effects */}
      <div className="scanlines" />
      <div className="vignette" />

      {/* Status bar */}
      <div className="status-bar">
        <div className="status-bar-left">
          <span className="status-item"><span className="status-dot" />FFIS v2.0</span>
          <span className="status-item">TACTICAL WORKSTATION</span>
        </div>
        <div className="status-bar-right">
          <span className="status-item status-amber">CONCEPTS: 0 LOADED</span>
          <span className="status-item status-dim">{clock}</span>
        </div>
      </div>

      {/* Login card */}
      <div className="page">
        <div className="card">
          <div className="card-inner">

            {/* Header */}
            <div className="card-header">
              <div className="logo-row">
                <div className="logo-shield">
                  <div className="logo-shield-inner" />
                </div>
                <div className="logo-text">NerdBall<span className="r-accent">r</span></div>
              </div>
              <div className="logo-tagline">Football Field Intelligence System<span className="cursor" /></div>
            </div>

            {/* Tabs */}
            <div className="auth-tabs">
              <button
                className={`auth-tab ${tab === 'login' ? 'active' : ''}`}
                onClick={() => { setTab('login'); setError(''); }}
              >
                [ Sign In ]
              </button>
              <button
                className={`auth-tab ${tab === 'register' ? 'active' : ''}`}
                onClick={() => { setTab('register'); setError(''); }}
              >
                [ Register ]
              </button>
            </div>

            {/* Body */}
            <div className="card-body">

              {error && (
                <div className="error-msg">{error}</div>
              )}

              {tab === 'login' ? (
                <form onSubmit={handleLogin}>
                  <div className="field-group">
                    <label className="field-label">Email Address</label>
                    <div className="field-input-wrap">
                      <svg className="field-input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="1" y="3" width="14" height="10" rx="1" stroke="#00e5ff" strokeWidth="1.2" />
                        <path d="M1 5l7 5 7-5" stroke="#00e5ff" strokeWidth="1.2" />
                      </svg>
                      <input
                        type="email"
                        placeholder="coach@example.com"
                        autoComplete="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                      />
                    </div>
                  </div>

                  <div className="field-group">
                    <label className="field-label">Password</label>
                    <div className="field-input-wrap">
                      <svg className="field-input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="3" y="7" width="10" height="8" rx="1" stroke="#00e5ff" strokeWidth="1.2" />
                        <path d="M5 7V5a3 3 0 016 0v2" stroke="#00e5ff" strokeWidth="1.2" />
                      </svg>
                      <input
                        type={showPassword ? 'text' : 'password'}
                        placeholder="••••••••"
                        autoComplete="current-password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                      />
                      <button type="button" className="field-toggle" onClick={() => setShowPassword(!showPassword)}>
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                          <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" strokeWidth="1.2" />
                          <circle cx="8" cy="8" r="2" stroke="currentColor" strokeWidth="1.2" />
                        </svg>
                      </button>
                    </div>
                  </div>

                  <button className="btn-primary" type="submit" disabled={loading}>
                    <span>
                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M5 2H2a1 1 0 00-1 1v8a1 1 0 001 1h3M10 10l3-3-3-3M13 7H5" stroke="currentColor" strokeWidth="1.3" strokeLinecap="round" strokeLinejoin="round" />
                      </svg>
                      {loading ? '[ AUTHENTICATING... ]' : 'Access System'}
                    </span>
                  </button>

                  <div className="divider">
                    <div className="divider-line" />
                    <div className="divider-text">or continue without account</div>
                    <div className="divider-line" />
                  </div>

                  <button type="button" className="btn-ghost" onClick={handleGuest}>
                    Enter as Guest — 5 AI queries / session
                  </button>
                </form>
              ) : (
                <form onSubmit={handleRegister}>
                  <div className="field-group">
                    <label className="field-label">Display Name</label>
                    <div className="field-input-wrap">
                      <svg className="field-input-icon" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="5" r="3" stroke="#00e5ff" strokeWidth="1.2" />
                        <path d="M2 13c0-3 2.7-5 6-5s6 2 6 5" stroke="#00e5ff" strokeWidth="1.2" strokeLinecap="round" />
                      </svg>
                      <input
                        type="text"
                        placeholder="CoachBlitz44"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        required
                      />
                    </div>
                  </div>
                  <div className="field-group">
                    <label className="field-label">Email Address</label>
                    <div className="field-input-wrap">
                      <svg className="field-input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="1" y="3" width="14" height="10" rx="1" stroke="#00e5ff" strokeWidth="1.2" />
                        <path d="M1 5l7 5 7-5" stroke="#00e5ff" strokeWidth="1.2" />
                      </svg>
                      <input
                        type="email"
                        placeholder="coach@example.com"
                        autoComplete="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                      />
                    </div>
                  </div>
                  <div className="field-group">
                    <label className="field-label">Password</label>
                    <div className="field-input-wrap">
                      <svg className="field-input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="3" y="7" width="10" height="8" rx="1" stroke="#00e5ff" strokeWidth="1.2" />
                        <path d="M5 7V5a3 3 0 016 0v2" stroke="#00e5ff" strokeWidth="1.2" />
                      </svg>
                      <input
                        type="password"
                        placeholder="••••••••"
                        autoComplete="new-password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                      />
                    </div>
                  </div>
                  <div className="field-group">
                    <label className="field-label">Confirm Password</label>
                    <div className="field-input-wrap">
                      <svg className="field-input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="3" y="7" width="10" height="8" rx="1" stroke="#00e5ff" strokeWidth="1.2" />
                        <path d="M5 7V5a3 3 0 016 0v2" stroke="#00e5ff" strokeWidth="1.2" />
                      </svg>
                      <input
                        type="password"
                        placeholder="••••••••"
                        autoComplete="new-password"
                        value={passwordConfirm}
                        onChange={(e) => setPasswordConfirm(e.target.value)}
                        required
                      />
                    </div>
                  </div>
                  <button className="btn-primary" type="submit" disabled={loading} style={{ marginTop: 4 }}>
                    <span>
                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <circle cx="7" cy="7" r="6" stroke="currentColor" strokeWidth="1.3" />
                        <path d="M7 4v6M4 7h6" stroke="currentColor" strokeWidth="1.3" strokeLinecap="round" />
                      </svg>
                      {loading ? '[ CREATING... ]' : 'Create Account'}
                    </span>
                  </button>
                  <div className="divider">
                    <div className="divider-line" />
                    <div className="divider-text">or continue without account</div>
                    <div className="divider-line" />
                  </div>
                  <button type="button" className="btn-ghost" onClick={handleGuest}>
                    Enter as Guest
                  </button>
                </form>
              )}

            </div>

            {/* Footer */}
            <div className="card-footer">
              <span className="card-footer-text">NerdBallr &middot; FFIS</span>
              <div className="token-row">
                <div className="token-sm t-circle" style={{ background: '#3B82F6' }} />
                <div className="token-sm t-square" style={{ background: '#22C55E' }} />
                <div className="token-sm t-tri" style={{ background: '#EAB308' }} />
                <div className="token-sm t-diamond" style={{ background: '#EF4444' }} />
                <div className="token-sm t-square" style={{ background: '#A855F7' }} />
                <div className="token-sm t-circle" style={{ background: '#F97316' }} />
              </div>
              <span className="card-footer-text">v0.7.0</span>
            </div>

          </div>
        </div>
      </div>

      <div className="sys-label">SYS &middot; DEV &middot; 0001</div>
      <div className="version-badge">NERDBALLR &copy; 2025</div>
    </>
  );
}
