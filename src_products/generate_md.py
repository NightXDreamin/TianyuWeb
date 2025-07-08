#!/usr/bin/env python3
"""
Generate .md stubs for Tianyu product list.

• Creates 6 sub-folders: water, water_supply, gas, dust, disinfection, parts
• File names come from the English equipment names, with spaces / special chars
  replaced by underscores and collapsed to one “_”.
• Each .md gets the same template text; edit TEMPLATE below or supply template.txt
  in the same directory to override.

Author: ChatGPT  (2025-07-08)
"""
from pathlib import Path
import re
import sys

# ---------- 1.  product lists ----------
PRODUCTS = {
    "water": [
        "Mechanical bar screens",
        "Rotary-drum fine screens",
        "Cable-operated trash rakes",
        "Traveling chain (rotary) bar screens",
        "Curved bar screens",
        "In-line grinder screens / comminutors",
        "Screw press compactors",
        "Vortex-flow grit chambers",
        "Grit classifiers (sand-water separators)",
        "Decanter centrifuges",
        "Shaftless screw conveyors",
        "Belt conveyors",
        "Full-bridge, peripheral-drive sludge scrapers (circular clarifiers)",
        "Center-drive clarifier scrapers",
        "Siphon suction sludge collectors",
        "Suction-type sludge collectors for rectangular tanks",
        "Radial-flow clarifier, peripheral-drive scraper with direct discharge",
        "Cable-drive sludge scrapers",
        "Traveling-bridge sludge scrapers",
        "Surface skimmers / scum skimmers",
        "High-speed clarifier mixers",
        "Horizontal screw-discharge settling centrifuges",
        "Belt filter presses",
        "Chamber (plate-and-frame) filter presses",
        "Low-speed propeller mixers (axial-flow)",
        "General-purpose mixing agitators",
        "Submersible mixers",
        "MBR (membrane-bioreactor) packages",
        "Buried steel packaged sewage plants",
        "Buried FRP packaged sewage plants",
        "Dissolved-air flotation (DAF) clarifiers",
        "Emulsion-wastewater treatment skids",
        "Slaughterhouse wastewater systems",
        "Surface-oil skimmers",
        "Land-based oil-water separators",
        "Integrated air-flotation units",
        "Cavitation-air flotation units",
        "BS-type air-flotation units",
        "Up-flow clarifiers",
        "Rotary-shear flotation cells",
        "Shallow-tank high-efficiency DAF units",
        "Automatic clarifiers",
        "Inclined-tube settlers",
        "Lamella plate clarifiers",
        "Anaerobic treatment reactors",
        "Biological contact-oxidation towers",
    ],
    "water_supply": [
        "Swimming-pool filters",
        "Pool recirculation equipment",
        "Fountain equipment (various styles)",
        "Continuous sand filters",
        "KG-L series water purifiers",
        "Package (compact) water-treatment units",
        "Shallow-bed filters",
        "Fully automatic water purifiers",
        "Sludge-water separators",
        "Iron-and-manganese removal filters",
        "High-efficiency pressure filters",
        "Cartridge precision (polishing) filters",
        "Multimedia pressure sand filters",
        "Activated-carbon filters",
        "Israeli ARKAL disc filters",
        "Self-cleaning back-wash filters",
        "Blow-down / back-flush filters",
        "Manual brush-type filters",
        "Guard (security) filters",
        "Fluoride-removal skids",
        "Walnut-shell media filters",
        "Fiber-bundle filters",
        "Fully automatic back-wash filters",
        "New gravity gap-free filters",
        "Fiber-ball filters",
        "High-rate medium-rate filters",
        "Large-scale desalination (turn-key plants)",
        "Ion-exchange vessels",
        "Reverse-osmosis RO desalination systems",
        "Ultrafiltration UF skids",
        "CO2 stripping degassing towers",
        "EDL electro-deionization units",
        "Mixed-bed anion cation floating-bed exchangers",
        "Anion and cation-exchange units",
        "JM automatic softeners",
        "Fully automatic water-softening systems",
        "Modular softening skids",
        "Ambient-temperature deaerators",
        "Vacuum deaerators",
        "Thermal deaerators",
        "All-in-one water-conditioning devices",
        "High-voltage electrostatic water conditioners",
        "Automatic pneumatic booster-pump systems",
        "VFD constant-pressure booster systems",
    ],
    "gas": [
        "Acid alkali gas-scrubbing towers",
        "Thermal oxidizers incinerators",
        "Packed-bed exhaust-gas scrubbers",
        "Odor-control deodorization units",
        "Flue-gas desulfurization denitrification equipment",
    ],
    "dust": [
        "Electrostatic precipitators ESP",
        "High-efficiency wet-film scrubbers",
        "Cyclone dust collectors",
    ],
    "disinfection": [
        "Fully automatic chemical-dosing skids",
        "Power-plant chemical-feed systems",
        "General chemical-dosing units",
        "In-line static mixers",
        "WA-series dosing skids",
        "Powder-metering feeders",
        "Submersible dosing systems",
        "Automatic dry-powder feeders",
        "Chlorine-dioxide generators chemical method",
        "Ozone generators",
        "UV disinfection reactors",
        "Chlorine-dioxide generators electrolytic",
        "Electronic scale-removal devices",
        "Sodium-hypochlorite generators",
    ],
    "parts": [
        "Corrugated structured packing",
        "Hexagonal honeycomb media",
        "Spray nozzles various types",
        "Drift mist eliminators",
        "Hollow plastic balls",
        "Active bio-media moving-bed",
        "Square-grid packing",
        "Flexible elastic bio-filler",
        "Semi-soft packing media",
        "Double-ring combined packing",
        "Ceramic Raschig rings",
        "Braided-rope packing",
        "Plastic Pall flower rings",
        "Alumina ceramic balls",
        "Step rings",
        "ABS strainers filter nozzles",
        "Stainless-steel wedge-wire screens",
        "Stainless-steel sluice gates",
        "Flat slide gates",
        "Gate-hoist mechanisms",
        "Adjustable weir gates",
        "Cast-iron square gates bronze-seated",
        "Cast-iron round gates bronze-seated",
        "Cast-iron tide flap gates",
        "PAC coagulant",
        "Anthracite filter media",
        "Silica-sand filter media",
        "Ceramic ceramsite filter media",
        "Sectional welded stainless-steel water tanks",
        "Vertical insulated water tanks",
        "Spherical water tanks",
        "Fully sealed sanitary metal water tanks",
        "Stainless-steel sanitary water tanks",
        "FRP filament-wound storage tanks",
        "FRP ductwork",
        "Pressure-resistant corrosion-proof tanks",
        "FRP storage vessels",
        "Skylight domes daylighting covers",
        "FRP cover panels",
    ],
}

# ---------- 2.  template ----------
TEMPLATE_PATH = Path(__file__).with_name("template.txt")
if TEMPLATE_PATH.exists():
    TEMPLATE = TEMPLATE_PATH.read_text(encoding="utf-8")
else:
    TEMPLATE = """\
# {{TITLE}}

> _Replace this paragraph with real content._

---
"""

# ---------- 3.  utilities ----------
def slugify(name: str) -> str:
    """Convert equipment name to safe file name."""
    # Replace non-alphanum with underscores
    slug = re.sub(r"[^\w]+", "_", name, flags=re.UNICODE)
    # Collapse multiple underscores
    slug = re.sub(r"__+", "_", slug)
    # Trim leading/trailing underscores
    return slug.strip("_")

def main(root: Path):
    for folder, items in PRODUCTS.items():
        target_dir = root / folder
        target_dir.mkdir(exist_ok=True)
        for item in items:
            fname = slugify(item)
            fpath = target_dir / f"{fname}.md"
            if not fpath.exists():
                # Insert the title into template
                content = TEMPLATE.replace("{{TITLE}}", item)
                fpath.write_text(content, encoding="utf-8")
                print(f"[+]  {fpath.relative_to(root)}")
            else:
                print(f"[=]  {fpath.relative_to(root)}  (exists, skipped)")

if __name__ == "__main__":
    try:
        main(Path.cwd())
    except KeyboardInterrupt:
        sys.exit(1)
