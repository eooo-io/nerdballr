import { useState, useRef, useEffect } from 'react';
import { useAiStore } from '@/stores/aiStore';
import { useAuthStore } from '@/stores/authStore';
import { AiMessage } from './AiMessage';
import './AiSidebar.css';

interface AiSidebarProps {
  conceptSlugs: string[];
}

const GUEST_LIMIT = 5;
const AUTH_LIMIT = 20;

export function AiSidebar({ conceptSlugs }: AiSidebarProps) {
  const { messages, isLoading, error, isOpen, queriesUsed, toggle, sendQuery, clearChat } = useAiStore();
  const user = useAuthStore((s) => s.user);
  const [input, setInput] = useState('');
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);

  const limit = user ? AUTH_LIMIT : GUEST_LIMIT;
  const remaining = Math.max(0, limit - queriesUsed);
  const atLimit = remaining <= 0;

  // Auto-scroll to bottom on new messages
  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages, isLoading]);

  // Focus input when sidebar opens
  useEffect(() => {
    if (isOpen) inputRef.current?.focus();
  }, [isOpen]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const q = input.trim();
    if (!q || isLoading || atLimit) return;
    setInput('');
    sendQuery(q, conceptSlugs);
  };

  return (
    <>
      {/* Toggle button */}
      <button className="ai-toggle" onClick={toggle} title="AI Assistant">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
          <path d="M9 1v2M9 15v2M1 9h2M15 9h2M3.34 3.34l1.41 1.41M13.24 13.24l1.41 1.41M3.34 14.66l1.41-1.41M13.24 4.76l1.41-1.41" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
          <circle cx="9" cy="9" r="3.5" stroke="currentColor" strokeWidth="1.5" />
        </svg>
        {messages.length > 0 && <span className="ai-badge">{messages.length}</span>}
      </button>

      {/* Sidebar panel */}
      {isOpen && (
        <aside className="ai-sidebar">
          {/* Header */}
          <div className="ai-header">
            <div className="ai-header-left">
              <span className="ai-header-title">Tactical Advisor</span>
              <span className="ai-header-remaining">
                {remaining}/{limit} queries
              </span>
            </div>
            <div className="ai-header-actions">
              {messages.length > 0 && (
                <button className="ai-clear-btn" onClick={clearChat} title="Clear chat">
                  Clear
                </button>
              )}
              <button className="ai-close-btn" onClick={toggle} title="Close">
                &times;
              </button>
            </div>
          </div>

          {/* Messages */}
          <div className="ai-messages">
            {messages.length === 0 && !isLoading && (
              <div className="ai-empty">
                <p className="ai-empty-text">Ask about the loaded concept.</p>
                <div className="ai-suggestions">
                  <button className="ai-suggestion" onClick={() => { setInput('Explain this concept'); }}>
                    Explain this concept
                  </button>
                  <button className="ai-suggestion" onClick={() => { setInput('What counters this?'); }}>
                    What counters this?
                  </button>
                  <button className="ai-suggestion" onClick={() => { setInput('Pre-snap read keys'); }}>
                    Pre-snap read keys
                  </button>
                </div>
              </div>
            )}

            {messages.map((msg) => (
              <AiMessage key={msg.id} message={msg} />
            ))}

            {isLoading && (
              <div className="ai-thinking">
                <span className="ai-thinking-dot" />
                <span className="ai-thinking-dot" />
                <span className="ai-thinking-dot" />
              </div>
            )}

            {error && <div className="ai-error">{error}</div>}

            <div ref={messagesEndRef} />
          </div>

          {/* Input */}
          <form className="ai-input-form" onSubmit={handleSubmit}>
            <input
              ref={inputRef}
              type="text"
              className="ai-input"
              placeholder={atLimit ? 'Query limit reached' : 'Ask about this concept...'}
              value={input}
              onChange={(e) => setInput(e.target.value)}
              disabled={isLoading || atLimit}
            />
            <button
              type="submit"
              className="ai-send-btn"
              disabled={!input.trim() || isLoading || atLimit}
            >
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M1 7h10M8 4l3 3-3 3" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </button>
          </form>
        </aside>
      )}
    </>
  );
}
