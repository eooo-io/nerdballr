import { Component } from 'react';
import type { ReactNode, ErrorInfo } from 'react';
import { Link } from 'react-router-dom';

interface Props {
  children: ReactNode;
}

interface State {
  hasError: boolean;
  error: Error | null;
}

export class ErrorBoundary extends Component<Props, State> {
  constructor(props: Props) {
    super(props);
    this.state = { hasError: false, error: null };
  }

  static getDerivedStateFromError(error: Error): State {
    return { hasError: true, error };
  }

  componentDidCatch(error: Error, info: ErrorInfo) {
    console.error('ErrorBoundary caught:', error, info.componentStack);
  }

  render() {
    if (this.state.hasError) {
      return (
        <div style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          minHeight: '100vh',
          gap: '16px',
          fontFamily: "'Share Tech Mono', monospace",
          color: '#e2e8f0',
        }}>
          <div style={{
            width: '48px',
            height: '48px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            border: '2px solid #ef4444',
            color: '#ef4444',
            fontSize: '24px',
            fontWeight: 'bold',
          }}>
            !
          </div>
          <p style={{ fontSize: '12px', color: '#94a3b8', maxWidth: '400px', textAlign: 'center', lineHeight: 1.6 }}>
            {this.state.error?.message || 'Something went wrong.'}
          </p>
          <button
            onClick={() => this.setState({ hasError: false, error: null })}
            style={{
              fontFamily: "'Share Tech Mono', monospace",
              fontSize: '10px',
              letterSpacing: '0.1em',
              textTransform: 'uppercase',
              color: '#00e5ff',
              background: 'transparent',
              border: '1px solid rgba(0, 229, 255, 0.3)',
              padding: '8px 20px',
              cursor: 'pointer',
            }}
          >
            Try Again
          </button>
          <Link
            to="/"
            onClick={() => this.setState({ hasError: false, error: null })}
            style={{
              fontFamily: "'Share Tech Mono', monospace",
              fontSize: '10px',
              letterSpacing: '0.1em',
              textTransform: 'uppercase',
              color: '#0096aa',
              textDecoration: 'none',
            }}
          >
            Back to Library
          </Link>
        </div>
      );
    }

    return this.props.children;
  }
}
