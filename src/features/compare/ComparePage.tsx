import { useEffect, useState, useCallback, useMemo } from 'react';
import { Link } from 'react-router-dom';
import { useCompareStore } from '@/stores/compareStore';
import { listConcepts } from '@/api/concepts';
import { AnimatedField, PlaybackControls } from '@/components/field';
import { AiSidebar } from '@/components/ai';
import { usePlaybackStore } from '@/stores/playbackStore';
import type { ConceptSummary } from '@/types';
import './ComparePage.css';

export function ComparePage() {
  const { slotA, slotB, synced, isLoadingA, isLoadingB, loadSlotA, loadSlotB, toggleSynced, clearSlots } = useCompareStore();
  const reset = usePlaybackStore((s) => s.reset);
  const [concepts, setConcepts] = useState<ConceptSummary[]>([]);
  const [catalogLoading, setCatalogLoading] = useState(true);

  // Load concept catalog
  useEffect(() => {
    listConcepts()
      .then((res) => setConcepts(res.data))
      .catch(() => {})
      .finally(() => setCatalogLoading(false));
  }, []);

  // Reset playback on unmount
  useEffect(() => {
    return () => {
      clearSlots();
      reset();
    };
  }, [clearSlots, reset]);

  const conceptSlugs = useMemo(() => {
    const slugs: string[] = [];
    if (slotA) slugs.push(slotA.slug);
    if (slotB) slugs.push(slotB.slug);
    return slugs;
  }, [slotA, slotB]);

  const handleSelectA = useCallback((slug: string) => {
    if (slug) {
      reset();
      loadSlotA(slug);
    }
  }, [loadSlotA, reset]);

  const handleSelectB = useCallback((slug: string) => {
    if (slug) {
      reset();
      loadSlotB(slug);
    }
  }, [loadSlotB, reset]);

  return (
    <div className="compare-container">
      {/* Header */}
      <header className="compare-header">
        <Link to="/" className="compare-back">&larr; Library</Link>
        <h1 className="compare-title">Compare Mode</h1>
        <button
          className={`compare-sync-btn ${synced ? 'active' : ''}`}
          onClick={toggleSynced}
        >
          {synced ? 'Synced' : 'Independent'}
        </button>
      </header>

      {/* Selectors row */}
      <div className="compare-selectors">
        <ConceptSelector
          label="Slot A"
          concepts={concepts}
          loading={catalogLoading}
          selected={slotA?.slug}
          onSelect={handleSelectA}
        />
        <div className="compare-vs">VS</div>
        <ConceptSelector
          label="Slot B"
          concepts={concepts}
          loading={catalogLoading}
          selected={slotB?.slug}
          onSelect={handleSelectB}
        />
      </div>

      {/* Side-by-side fields */}
      <div className="compare-fields">
        <div className="compare-slot">
          {isLoadingA && <div className="compare-loading">Loading...</div>}
          {slotA && !isLoadingA && (
            <>
              <div className="compare-slot-label">{slotA.label}</div>
              <AnimatedField concept={slotA} className="compare-field" />
            </>
          )}
          {!slotA && !isLoadingA && (
            <div className="compare-empty">Select a concept</div>
          )}
        </div>

        <div className="compare-slot">
          {isLoadingB && <div className="compare-loading">Loading...</div>}
          {slotB && !isLoadingB && (
            <>
              <div className="compare-slot-label">{slotB.label}</div>
              <AnimatedField concept={slotB} className="compare-field" />
            </>
          )}
          {!slotB && !isLoadingB && (
            <div className="compare-empty">Select a concept</div>
          )}
        </div>
      </div>

      {/* Shared playback when synced and both loaded */}
      {synced && slotA && slotB && (
        <div className="compare-playback">
          <PlaybackControls phases={slotA.phases} />
        </div>
      )}

      {/* AI Assistant */}
      {conceptSlugs.length > 0 && (
        <AiSidebar conceptSlugs={conceptSlugs} />
      )}
    </div>
  );
}

/* ─── Concept selector dropdown ──────────────────────────── */

interface ConceptSelectorProps {
  label: string;
  concepts: ConceptSummary[];
  loading: boolean;
  selected?: string;
  onSelect: (slug: string) => void;
}

function ConceptSelector({ label, concepts, loading, selected, onSelect }: ConceptSelectorProps) {
  return (
    <div className="concept-selector">
      <label className="concept-selector-label">{label}</label>
      <select
        className="concept-selector-select"
        value={selected || ''}
        onChange={(e) => onSelect(e.target.value)}
        disabled={loading}
      >
        <option value="">— Choose concept —</option>
        {concepts.map((c) => (
          <option key={c.slug} value={c.slug}>
            {c.label} ({c.category.replace('-', ' ')})
          </option>
        ))}
      </select>
    </div>
  );
}
