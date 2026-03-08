import type { Annotation as AnnotationType } from '@/types';

interface AnnotationProps {
  annotation: AnnotationType;
}

export function AnnotationComponent({ annotation }: AnnotationProps) {
  const { position, text, style, color = '#e2e8f0' } = annotation;

  switch (style) {
    case 'label':
      return (
        <text
          x={position.x}
          y={position.y}
          fill={color}
          fontSize="10"
          fontFamily="monospace"
          fontWeight="bold"
          textAnchor="middle"
          dominantBaseline="central"
          opacity="0.8"
        >
          {text}
        </text>
      );

    case 'callout':
      return (
        <g>
          <rect
            x={position.x - 4}
            y={position.y - 10}
            width={text.length * 6.5 + 8}
            height={18}
            rx="2"
            fill="rgba(0,0,0,0.7)"
            stroke={color}
            strokeWidth="0.8"
            opacity="0.8"
          />
          <text
            x={position.x + text.length * 3.25}
            y={position.y}
            fill={color}
            fontSize="9"
            fontFamily="monospace"
            textAnchor="middle"
            dominantBaseline="central"
          >
            {text}
          </text>
        </g>
      );

    case 'arrow-label':
      return (
        <g>
          <line
            x1={position.x}
            y1={position.y}
            x2={position.x}
            y2={position.y - 20}
            stroke={color}
            strokeWidth="1"
            opacity="0.5"
          />
          <text
            x={position.x}
            y={position.y - 24}
            fill={color}
            fontSize="9"
            fontFamily="monospace"
            fontWeight="bold"
            textAnchor="middle"
            opacity="0.8"
          >
            {text}
          </text>
        </g>
      );

    case 'bracket': {
      const halfWidth = text.length * 3;
      return (
        <g>
          <path
            d={`M${position.x - halfWidth},${position.y + 5} L${position.x - halfWidth},${position.y + 10} L${position.x + halfWidth},${position.y + 10} L${position.x + halfWidth},${position.y + 5}`}
            fill="none"
            stroke={color}
            strokeWidth="1"
            opacity="0.5"
          />
          <text
            x={position.x}
            y={position.y}
            fill={color}
            fontSize="9"
            fontFamily="monospace"
            fontWeight="bold"
            textAnchor="middle"
            dominantBaseline="central"
            opacity="0.8"
          >
            {text}
          </text>
        </g>
      );
    }
  }
}
