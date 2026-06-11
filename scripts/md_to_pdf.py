#!/usr/bin/env python3
"""Convert curriculum Markdown files to PDF using fpdf2."""

from __future__ import annotations

import re
import sys
from pathlib import Path

import markdown
from fpdf import FPDF

ROOT = Path(__file__).resolve().parent.parent
PDF_DIR = ROOT / "docs" / "pdf"
DOCS_DIR = ROOT / "docs"

FONT_DIR = Path("C:/Windows/Fonts")
FONT_REGULAR = FONT_DIR / "arial.ttf"
FONT_BOLD = FONT_DIR / "arialbd.ttf"
FONT_ITALIC = FONT_DIR / "ariali.ttf"
FONT_MONO = FONT_DIR / "consola.ttf"

UNICODE_REPLACEMENTS = {
    "\u2192": "->",
    "\u2190": "<-",
    "\u2713": "[x]",
    "\u2717": "[ ]",
    "\u2248": "~",
    "\u2014": "-",
    "\u2013": "-",
    "\u251c": "|",
    "\u2514": "`",
    "\u2500": "-",
    "\u2502": "|",
    "\u2026": "...",
}


def collect_markdown_files() -> list[Path]:
    files = [ROOT / "README.md"]
    files.extend(sorted(DOCS_DIR.glob("[0-9]*.md")))
    files.append(ROOT / "taskforge" / "README.md")
    return [f for f in files if f.exists()]


def sanitize(text: str) -> str:
    for old, new in UNICODE_REPLACEMENTS.items():
        text = text.replace(old, new)
    return text.encode("ascii", errors="replace").decode("ascii")


def fix_html_for_fpdf(html: str) -> str:
    """Flatten HTML structures that fpdf2 cannot render."""
    html = re.sub(
        r"<details>\s*<summary>(.*?)</summary>",
        r"<p><b>\1</b></p>",
        html,
        flags=re.DOTALL | re.IGNORECASE,
    )
    html = html.replace("</details>", "")

    def clean_cell(match: re.Match[str]) -> str:
        tag = match.group(1)
        content = match.group(2)
        content = re.sub(r"<[^>]+>", "", content)
        return f"<{tag}>{content}</{tag}>"

    html = re.sub(
        r"<(td|th)>(.*?)</\1>",
        clean_cell,
        html,
        flags=re.DOTALL | re.IGNORECASE,
    )
    return html


def md_to_html(md_text: str) -> str:
    md_text = sanitize(md_text)
    html = markdown.markdown(
        md_text,
        extensions=["tables", "fenced_code", "nl2br", "sane_lists"],
    )
    return fix_html_for_fpdf(html)


def pdf_output_name(md_path: Path) -> str:
    if md_path.parent == DOCS_DIR:
        return md_path.stem + ".pdf"
    if md_path.name == "README.md" and md_path.parent == ROOT:
        return "00-curriculum-overview.pdf"
    return "taskforge-readme.pdf"


def create_pdf() -> FPDF:
    if not FONT_REGULAR.exists():
        raise FileNotFoundError(f"Font not found: {FONT_REGULAR}")

    pdf = FPDF()
    pdf.set_auto_page_break(auto=True, margin=15)
    pdf.add_font("Arial", "", str(FONT_REGULAR))
    pdf.add_font("Arial", "B", str(FONT_BOLD))
    pdf.add_font("Arial", "I", str(FONT_ITALIC))
    if FONT_MONO.exists():
        pdf.add_font("Consolas", "", str(FONT_MONO))
    pdf.set_font("Arial", size=11)
    return pdf


def convert_to_pdf(html: str, pdf_path: Path) -> None:
    pdf = create_pdf()
    pdf.add_page()
    pdf.write_html(html, font_family="Arial")
    pdf_path.parent.mkdir(parents=True, exist_ok=True)
    pdf.output(str(pdf_path))


def main() -> int:
    files = collect_markdown_files()
    if not files:
        print("No markdown files found.")
        return 1

    print(f"Converting {len(files)} files to PDF...")
    print(f"Output: {PDF_DIR}\n")

    success = 0
    combined_parts: list[str] = []

    for md_path in files:
        pdf_name = pdf_output_name(md_path)
        pdf_path = PDF_DIR / pdf_name
        rel = md_path.relative_to(ROOT)

        print(f"  {rel} -> docs/pdf/{pdf_name} ... ", end="", flush=True)

        try:
            text = md_path.read_text(encoding="utf-8")
            html = md_to_html(text)
            convert_to_pdf(html, pdf_path)
            print("OK")
            success += 1

            if md_path != ROOT / "taskforge" / "README.md":
                combined_parts.append(
                    '<div style="page-break-before: always;"></div>' + html
                )
        except Exception as exc:
            print(f"ERROR: {exc!s}")

    if combined_parts:
        print("\nBuilding combined PDF...")
        combined_path = PDF_DIR / "Laravel-Complete-Learning-Guide.pdf"
        try:
            convert_to_pdf("".join(combined_parts), combined_path)
            print("  docs/pdf/Laravel-Complete-Learning-Guide.pdf ... OK")
        except Exception as exc:
            print(f"  Combined PDF ERROR: {exc!s}")

    print(f"\nDone: {success}/{len(files)} PDFs in docs/pdf/")
    return 0 if success == len(files) else 1


if __name__ == "__main__":
    sys.exit(main())
