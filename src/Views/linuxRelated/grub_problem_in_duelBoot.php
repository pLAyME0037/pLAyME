<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arch Linux & Windows Dual Boot GRUB Troubleshooting Guide</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f6;
            color: #333;
        }
        header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        h1, h2, h3 {
            color: #2c3e50;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        h1 {
            color: #ecf0f1;
            font-size: 2.5em;
        }
        h2 {
            font-size: 1.8em;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        h3 {
            font-size: 1.4em;
            color: #34495e;
        }
        section {
            background-color: #ffffff;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        pre {
            background-color: #ecf0f1;
            color: #2c3e50;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9em;
            margin-bottom: 15px;
            white-space: pre-wrap; /* Allows long lines to wrap */
            word-wrap: break-word; /* Ensures words break */
        }
        code {
            background-color: #ecf0f1;
            color: #c0392b;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 0.9em;
        }
        ul, ol {
            margin-left: 20px;
            padding-left: 0;
        }
        li {
            margin-bottom: 10px;
        }
        strong {
            color: #e67e22;
        }
        footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-size: 0.9em;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Arch Linux & Windows Dual Boot GRUB Troubleshooting Guide</h1>
        <p>Restoring Your GRUB Boot Menu After Windows Takes Over</p>
    </header>

    <main>
        <section id="introduction">
            <h2>Introduction</h2>
            <p>If you're dual-booting Arch Linux and Windows, it's common for a Windows installation or major update to overwrite your GRUB bootloader, leading to your Arch Linux entry disappearing from the boot menu. This guide provides a comprehensive set of steps to restore GRUB and regain access to your Arch Linux installation.</p>
        </section>

        <section id="prerequisites">
            <h2>Prerequisites</h2>
            <ul>
                <li>An Arch Linux installation medium (USB drive or DVD).</li>
                <li>A basic understanding of your system's partition layout (specifically your Arch Linux root partition and your EFI System Partition).</li>
            </ul>
        </section>

        <section id="core-steps">
            <h2>Core Troubleshooting Steps</h2>
            <p>These steps assume you've already identified your Arch Linux root partition (e.g., <code>/dev/sdXn</code>) and your EFI System Partition (ESP) (e.g., <code>/dev/sdYm</code>, usually FAT32).</p>

            <ol>
                <li>
                    <h3>Boot into Arch Linux Live Environment</h3>
                    <p>Start your computer and boot from your Arch Linux installation USB or DVD. Select the option to boot into the live environment.</p>
                </li>

                <li>
                    <h3>Identify and Mount Your Partitions Correctly</h3>
                    <p>Open a terminal and identify your partitions. It's crucial to correctly mount your Arch Linux root partition to <code>/mnt</code> and your EFI System Partition to <code>/mnt/boot/efi</code>.</p>
                    <pre><code># Identify your partitions (look for Arch Linux root and FAT32 EFI partition)
lsblk -f

# Unmount any previous attempts
umount -R /mnt

# Mount your Arch Linux Root Partition (replace /dev/sdXn with your actual root)
mount /dev/sdXn /mnt

# Create the mount point for EFI if it doesn't exist
mkdir -p /mnt/boot/efi

# Mount your EFI System Partition (replace /dev/sdYm with your actual EFI partition)
mount /dev/sdYm /mnt/boot/efi

# Verify mounts before chrooting
lsblk</code></pre>
                    <p><strong>Verification:</strong> Ensure <code>/dev/sdXn</code> is mounted on <code>/mnt</code> and <code>/dev/sdYm</code> on <code>/mnt/boot/efi</code>. If you have a separate <code>/boot</code> partition, mount it at <code>/mnt/boot</code> *before* mounting <code>/mnt/boot/efi</code>.</p>
                </li>

                <li>
                    <h3>Chroot into Your Installed System</h3>
                    <p>This command allows you to operate as if you are directly on your installed Arch Linux system.</p>
                    <pre><code>arch-chroot /mnt</code></pre>
                    <p><strong>Verification:</strong> Your terminal prompt should change (e.g., to <code>[root@archlinux /]#</code>).</p>
                    <p><strong>Crucial Inside Chroot Verification:</strong> Confirm <code>/boot</code> and <code>/boot/efi</code> are correctly mounted from the chroot's perspective:</p>
                    <pre><code>findmnt /boot
findmnt /boot/efi</code></pre>
                    <p><code>/boot</code> should show your Arch Linux root or separate <code>/boot</code> partition. <code>/boot/efi</code> should show your FAT32 EFI partition.</p>
                </li>

                <li>
                    <h3>Install/Enable <code>os-prober</code></h3>
                    <p><code>os-prober</code> is essential for GRUB to detect other operating systems like Windows.</p>
                    <pre><code># Install os-prober if not already installed
pacman -S os-prober

# Open the GRUB default configuration file
nano /etc/default/grub</code></pre>
                    <p>Find the line <code>#GRUB_DISABLE_OS_PROBER=true</code> or <code>GRUB_DISABLE_OS_PROBER=true</code>. **Change it to:**</p>
                    <pre><code>GRUB_DISABLE_OS_PROBER=false</code></pre>
                    <p>Save (Ctrl+O, Enter) and exit (Ctrl+X) nano.</p>
                    <p>You can optionally run <code>os-prober</code> directly to see if it detects Windows:</p>
                    <pre><code>os-prober</code></pre>
                </li>

                <li>
                    <h3>Reinstall GRUB Bootloader</h3>
                    <p>Reinstall GRUB to your EFI System Partition. The <code>--bootloader-id</code> can be anything, but "GRUB" is common.</p>
                    <pre><code>grub-install --target=x86_64-efi --efi-directory=/boot/efi --bootloader-id=GRUB --recheck</code></pre>
                    <p><strong>Note:</strong> If your EFI partition was mounted at <code>/boot</code> instead of <code>/boot/efi</code>, adjust the <code>--efi-directory</code> path accordingly (e.g., <code>--efi-directory=/boot</code>).</p>
                </li>

                <li>
                    <h3>Verify Kernel & Initramfs Files and Regenerate GRUB Configuration</h3>
                    <p>The Arch Linux entry depends on GRUB finding your kernel and initramfs files, usually in <code>/boot</code>.</p>
                    <pre><code># Check for kernel and initramfs files
ls -l /boot
ls /boot | grep vmlinuz # Should show vmlinuz-linux or similar
ls /boot | grep initramfs # Should show initramfs-linux.img or similar</code></pre>
                    <p>If these files are missing or you suspect corruption, reinstall the Linux kernel package:</p>
                    <pre><code>pacman -S linux</code></pre>
                    <p>(If you use a different kernel like <code>linux-lts</code>, reinstall that instead).</p>
                    <p>Finally, regenerate the GRUB configuration file. **Observe the output carefully!**</p>
                    <pre><code>grub-mkconfig -o /boot/grub/grub.cfg</code></pre>
                    <p>You **must** see lines like:</p>
                    <ul>
                        <li><code>Found Linux image: /boot/vmlinuz-linux</code></li>
                        <li><code>Found initrd image: /boot/initramfs-linux.img</code></li>
                        <li><code>Found Windows Boot Manager on /dev/sdY...</code> (or similar)</li>
                    </ul>
                    <p>If you don't see the "Found Linux image" lines, GRUB is not finding your Arch Linux installation, likely due to an incorrect mount point for <code>/boot</code> or missing kernel files.</p>
                </li>

                <li>
                    <h3>Exit Chroot and Reboot</h3>
                    <p>Once GRUB is configured, exit the chroot environment and unmount partitions before rebooting.</p>
                    <pre><code>exit
umount -R /mnt
reboot</code></pre>
                </li>
            </ol>
        </section>

        <section id="common-issues">
            <h2>Common Issues & Tips</h2>

            <h3>GRUB Appears, But Only Shows "UEFI Firmware Settings" (No OS Entries)</h3>
            <p>This indicates GRUB itself is loading, but its configuration file (`grub.cfg`) wasn't properly generated or `os-prober` failed. Revisit **Step 4 (Install/Enable `os-prober`)** and **Step 6 (Regenerate GRUB Configuration)**. Ensure <code>GRUB_DISABLE_OS_PROBER=false</code> in <code>/etc/default/grub</code> and verify the output of `grub-mkconfig` for "Found Linux image" and "Found Windows Boot Manager".</p>

            <h3>GRUB Shows Windows/Firmware, But Not Arch Linux</h3>
            <p>This is a common scenario. It means `os-prober` detected Windows, but GRUB failed to find your Arch Linux installation. The most likely causes are:</p>
            <ul>
                <li><strong>Incorrect Mounting:</strong> Your Arch Linux root partition or separate <code>/boot</code> partition was not mounted correctly at <code>/mnt</code> or <code>/mnt/boot</code> before chrooting. Go back to **Step 2** and verify with <code>lsblk</code> and `findmnt` inside the chroot (**Step 3**).</li>
                <li><strong>Missing Kernel/Initramfs:</strong> The essential kernel (`vmlinuz-linux`) and initramfs (`initramfs-linux.img`) files are missing or corrupted in your Arch Linux <code>/boot</code> directory. Reinstall the `linux` package as shown in **Step 6**.</li>
            </ul>

            <h3>GRUB Menu Still Doesn't Appear After Reboot</h3>
            <p>If GRUB doesn't appear at all, Windows might have set its boot manager as the default. Enter your system's UEFI/BIOS settings (often by pressing F2, F10, F12, or Del during boot-up) and adjust the boot order to prioritize "GRUB" or "Arch Linux" over "Windows Boot Manager".</p>

            <h3>Windows Fast Startup / Secure Boot</h3>
            <ul>
                <li><strong>Fast Startup:</strong> Windows' Fast Startup feature can sometimes lock disk partitions, making them inaccessible to Linux. If Windows is still bootable, disable Fast Startup in Windows (Control Panel > Power Options > Choose what the power buttons do > Change settings that are currently unavailable > uncheck "Turn on fast startup"). Then perform a full shutdown (not restart) before attempting the GRUB repair steps.</li>
                <li><strong>Secure Boot:</strong> While less common for the GRUB menu not appearing at all (it usually prevents GRUB from loading), ensure Secure Boot is disabled in your UEFI firmware settings if you haven't already.</li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Arch Linux Dual Boot Troubleshooting Guide</p>
    </footer>
</body>
</html>