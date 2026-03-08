import type { ChatMessage } from '@/stores/aiStore';

interface AiMessageProps {
  message: ChatMessage;
}

export function AiMessage({ message }: AiMessageProps) {
  const isUser = message.role === 'user';

  return (
    <div className={`ai-msg ${isUser ? 'ai-msg-user' : 'ai-msg-assistant'}`}>
      {!isUser && message.intent && (
        <span className="ai-msg-intent">{message.intent}</span>
      )}
      <div className="ai-msg-content">
        {message.content.split('\n').map((line, i) => (
          <p key={i} className="ai-msg-line">{line || '\u00A0'}</p>
        ))}
      </div>
    </div>
  );
}
