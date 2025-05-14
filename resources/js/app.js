import "./bootstrap";
import html2canvas from "html2canvas-pro";
import { jsPDF } from "jspdf";

import * as pdfjsLib from "pdfjs-dist/build/pdf";

pdfjsLib.GlobalWorkerOptions.workerSrc = new URL(
  'pdfjs-dist/build/pdf.worker.min.js',
  import.meta.url
).toString();

window.pdfjsLib = pdfjsLib;
window.html2canvas = html2canvas;
window.jsPDF = jsPDF;
